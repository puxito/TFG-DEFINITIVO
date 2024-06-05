<?php
require("../php/errores.php");
require("../php/funciones.php");
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Iniciar sesión y conectar a la base de datos
$conn = conectarBBDD();
sesionN1();

// Obtener el ID de la comida desde el parámetro GET
$idComida = $_GET['id'];

// Verificar si se está enviando un formulario de agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_producto"])) {
    $idProducto = $_POST["id_producto"];
    $cantidad = $_POST["cantidad"];
    agregarProductoAComida($idComida, $idProducto, $cantidad);
}

// Verificar si se está enviando un formulario de eliminar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_producto"])) {
    $idProducto = $_POST["id_producto"];
    eliminarProductoDeComida($idComida, $idProducto);
}

// Verificar si se está enviando un formulario de exportar a Excel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["exportar_excel"])) {
    exportarAExcel($idComida);
}

// Obtener los productos y los valores nutricionales de la comida
$productos = obtenerProductosPorComida($idComida);
$valoresNutricionales = obtenerTotalValoresNutricionales($idComida);

// Obtener todos los productos para el desplegable
$listaProductos = obtenerTodosLosProductos();
function exportarAExcel($idComida)
{
    global $conn;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Dieta');

    // Definir estilos para los títulos
    $headerStyleArray = [
        'font' => [
            'bold' => true,
            'color' => ['argb' => Color::COLOR_WHITE],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF4CAF50'], // Verde
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    // Agregar encabezados
    $sheet->setCellValue('A1', 'Producto');
    $sheet->setCellValue('B1', 'Cantidad (g)');
    $sheet->setCellValue('C1', 'Calorías');
    $sheet->setCellValue('D1', 'Carbohidratos');
    $sheet->setCellValue('E1', 'Grasas');
    $sheet->setCellValue('F1', 'Proteínas');

    // Aplicar estilos a los encabezados
    $sheet->getStyle('A1:F1')->applyFromArray($headerStyleArray);

    // Obtener productos de la comida
    $productos = obtenerProductosPorComida($idComida);
    $row = 2;
    while ($producto = $productos->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $producto["nombreProducto"]);
        $sheet->setCellValue('B' . $row, $producto["cantidad"]);
        $sheet->setCellValue('C' . $row, $producto["caloriasProducto"] * $producto["cantidad"] / 100);
        $sheet->setCellValue('D' . $row, $producto["hcarbonoProducto"] * $producto["cantidad"] / 100);
        $sheet->setCellValue('E' . $row, $producto["grasasProducto"] * $producto["cantidad"] / 100);
        $sheet->setCellValue('F' . $row, $producto["proteinasProducto"] * $producto["cantidad"] / 100);
        $row++;
    }

    // Escribir archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Dieta_' . $idComida . '.xlsx';
    $filePath = '../exportdietas' . $idComida . '.xlsx'; 

    $writer->save($filePath);

    // Descargar el archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);

    // Eliminar el archivo después de la descarga
    unlink($filePath);
    exit();
}

function agregarProductoAComida($idComida, $idProducto, $cantidad)
{
    global $conn;

    // Insertar el producto en la comida
    $stmt = $conn->prepare("INSERT INTO comidasProductos (idComidaFK, idProductoFK, cantidad) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $idComida, $idProducto, $cantidad);
    $stmt->execute();
    $stmt->close();

    // Actualizar los valores nutricionales totales de la comida
    actualizarValoresNutricionales($idComida);
}

function eliminarProductoDeComida($idComida, $idProducto)
{
    global $conn;

    // Obtener los valores nutricionales del producto que se va a eliminar
    $stmt = $conn->prepare("SELECT caloriasProducto, hcarbonoProducto, grasasProducto, proteinasProducto FROM productos WHERE idProducto = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $stmt->close();

    // Restar los valores nutricionales del producto eliminado de los valores totales de la comida
    $stmt = $conn->prepare("UPDATE comidas SET caloriasTotales = caloriasTotales - ?, hcarbonoTotales = hcarbonoTotales - ?, grasasTotales = grasasTotales - ?, proteinasTotales = proteinasTotales - ? WHERE idComida = ?");
    $stmt->bind_param("ddddi", $producto['caloriasProducto'], $producto['hcarbonoProducto'], $producto['grasasProducto'], $producto['proteinasProducto'], $idComida);
    $stmt->execute();
    $stmt->close();

    // Eliminar el producto de la comida
    $stmt = $conn->prepare("DELETE FROM comidasProductos WHERE idComidaFK = ? AND idProductoFK = ?");
    $stmt->bind_param("ii", $idComida, $idProducto);
    $stmt->execute();
    $stmt->close();
}


function actualizarValoresNutricionales($idComida)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE comidas c
                            JOIN (SELECT cp.idComidaFK,
                                         SUM(p.caloriasProducto * cp.cantidad / 100) AS totalCalorias,
                                         SUM(p.hcarbonoProducto * cp.cantidad / 100) AS totalCarbohidratos,
                                         SUM(p.grasasProducto * cp.cantidad / 100) AS totalGrasas,
                                         SUM(p.proteinasProducto * cp.cantidad / 100) AS totalProteinas
                                  FROM comidasProductos cp
                                  JOIN productos p ON cp.idProductoFK = p.idProducto
                                  WHERE cp.idComidaFK = ?
                                  GROUP BY cp.idComidaFK) AS subquery
                            ON c.idComida = subquery.idComidaFK
                            SET c.caloriasTotales = subquery.totalCalorias,
                                c.hcarbonoTotales = subquery.totalCarbohidratos,
                                c.grasasTotales = subquery.totalGrasas,
                                c.proteinasTotales = subquery.totalProteinas
                            WHERE c.idComida = ?");
    $stmt->bind_param("ii", $idComida, $idComida);
    $stmt->execute();
    $stmt->close();
}

function obtenerProductosPorComida($idComida)
{
    global $conn;

    $stmt = $conn->prepare("SELECT p.idProducto, p.nombreProducto, cp.cantidad, p.caloriasProducto, p.hcarbonoProducto, p.grasasProducto, p.proteinasProducto
                            FROM comidasProductos cp
                            JOIN productos p ON cp.idProductoFK = p.idProducto
                            WHERE cp.idComidaFK = ?");
    $stmt->bind_param("i", $idComida);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}

function obtenerTotalValoresNutricionales($idComida)
{
    global $conn;

    $stmt = $conn->prepare("SELECT caloriasTotales AS totalCalorias, hcarbonoTotales AS totalCarbohidratos, grasasTotales AS totalGrasas, proteinasTotales AS totalProteinas
                            FROM comidas
                            WHERE idComida = ?");
    $stmt->bind_param("i", $idComida);
    $stmt->execute();
    $result = $stmt->get_result();
    $valoresNutricionales = $result->fetch_assoc();
    $stmt->close();

    return $valoresNutricionales;
}

function obtenerTodosLosProductos()
{
    global $conn;

    $stmt = $conn->prepare("SELECT idProducto, nombreProducto FROM productos");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Comida</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg" style="background-color: #006691;">
        <div class="container-fluid">
            <a href="../index.php">
                <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item m-2">
                        <a href="../dietas/comidas.php">
                            <img src="../media/iconos/add.png" width="65" alt="Nueva Dieta">
                        </a>
                    </li>
                    <li class="nav-item m-2">
                        <a href="../prods/productos.php">
                            <img src="../media/iconos/productos.png" width="65" alt="Ver productos">
                        </a>
                    </li>
                </ul>
                <?php
                if (sesionN0()) {
                    // El usuario ha iniciado sesión

                    // Verificar si el usuario es administrador
                    $conexion = conectarBBDD();
                    $nombre_usuario = $_SESSION["correoElectronicoUsuario"];
                    $sql = "SELECT idRolFK FROM usuarios WHERE correoElectronicoUsuario = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("s", $nombre_usuario);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fila = $result->fetch_assoc();
                    $administrador = $fila["idRolFK"];
                    $stmt->close();
                    $conexion->close();


                    $nombre_usuario = obtenerNombreUsuario();
                    $ruta_imagen = obtenerRutaImagenUsuario();

                    echo '
                    <ul class="ms-auto m-2 navbar-nav">
                        <li class="border border-dark rounded dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <img class="rounded-circle" src="../' . $ruta_imagen . '" width="65" alt="Foto de Perfil">
                                Bienvenido: <span class="fw-bold">' . $nombre_usuario . '</span>
                            </a>
                        
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../perfil.php">Mi Perfil</a></li>';
                    if ($administrador == 1) {
                        echo '<li><a class="dropdown-item" href="../administracion/indexadmin.php">Panel de Control</a></li>';
                    }
                    echo '       
                                <form method="post">
                                    <input type="hidden" name="cerses" value="true">
                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                </form>

                            </ul>
                        </li>
                    </ul>'
                ?>
                <?php
                } else {
                ?>
                    <article class="ms-auto">
                        <h2 hidden>Inicio sesión</h2>
                        <form class="d-flex align-items-center" method="post">
                            <div class="">
                                <a class="btn btn-primary" href="../php/login.php">Iniciar Sesion</a>
                                <a class="btn btn-primary" href="../php/registro.php">Registrarse</a>
                            </div>
                        </form>
                    </article>

                <?php
                }
                ?>

                </section>
    </nav>
    <div class="container mt-5">
        <h2>Detalles de la Comida</h2>
        <div class="row">
            <div class="col-md-3">
                <h4>Valores Nutricionales Totales</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Calorías</th>
                        <td><?= $valoresNutricionales['totalCalorias'] ?></td>
                    </tr>
                    <tr>
                        <th>Carbohidratos</th>
                        <td><?= $valoresNutricionales['totalCarbohidratos'] ?></td>
                    </tr>
                    <tr>
                        <th>Grasas</th>
                        <td><?= $valoresNutricionales['totalGrasas'] ?></td>
                    </tr>
                    <tr>
                        <th>Proteínas</th>
                        <td><?= $valoresNutricionales['totalProteinas'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-9">
                <form method="post">
                    <div class="form-group">
                        <label for="id_producto">Agregar Producto</label>
                        <select class="form-control" id="id_producto" name="id_producto" required>
                            <?php while ($producto = $listaProductos->fetch_assoc()) { ?>
                                <option value="<?= $producto['idProducto'] ?>"><?= $producto['nombreProducto'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad (g)</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                    </div>
                    <button type="submit" name="agregar_producto" class="btn btn-success">Agregar Producto</button>
                </form>

                <h4 class="mt-4">Productos en la Comida</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad (g)</th>
                            <th>Calorías</th>
                            <th>Carbohidratos</th>
                            <th>Grasas</th>
                            <th>Proteínas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($producto = $productos->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $producto["nombreProducto"] ?></td>
                                <td><?= $producto["cantidad"] ?></td>
                                <td><?= $producto["caloriasProducto"] * $producto["cantidad"] / 100 ?></td>
                                <td><?= $producto["hcarbonoProducto"] * $producto["cantidad"] / 100 ?></td>
                                <td><?= $producto["grasasProducto"] * $producto["cantidad"] / 100 ?></td>
                                <td><?= $producto["proteinasProducto"] * $producto["cantidad"] / 100 ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="id_producto" value="<?= $producto['idProducto'] ?>">
                                        <button type="submit" name="eliminar_producto" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <form method="post" class="mt-3">
                    <button type="submit" name="exportar_excel" class="btn btn-primary">Exportar a Excel</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
