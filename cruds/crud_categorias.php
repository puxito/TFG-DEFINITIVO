<?php
// ARCHIVOS

require("../php/errores.php");
require("../php/funciones.php");

// VARIABLES
$mensaje = '';
sesionN2();
// Conexión con la BBDD
$conn = conectarBBDD();

//-------------SELECT------------//
$consultacategoria = "SELECT categorias.idCategoria, categorias.nombreCategoria, COUNT(productos.idProducto) AS numProductos
FROM categorias
LEFT JOIN productos ON categorias.idCategoria = productos.idCategoriaFK
GROUP BY categorias.idCategoria, categorias.nombreCategoria
";

$preparada = $conn->prepare($consultacategoria);
if ($preparada === false) {
    die("Error en la preparación: " . $conn->error);
}

$preparada->execute();

$resultado = $preparada->get_result();
$registros = $resultado->fetch_all(MYSQLI_ASSOC);

if ($registros === false) {
    die("Error en la ejecución: " . $conn->error);
}

//-------------DELETE------------//
if (isset($_POST['eliminar'])) {
    $idCategoria = $_POST['idCategoria'];

    $borrarcategoria = "DELETE FROM categorias WHERE idCategoria =?";

    $preparada = $conn->prepare($borrarcategoria);
    $preparada->bind_param("i", $idCategoria);

    if ($preparada->execute()) {
        $mensaje = "Categoría eliminada correctamente";
    } else {
        $mensaje = "Error al eliminar la categoría";
    }
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../estilos/adminstyle.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg" style="background-color: #006691;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a href="../index.php">
                    <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
                </a>
            </div>
            <div class="d-flex justify-content-center flex-grow-1">
                <h1 class="display-6 text-light text-center"><strong>Categorías</strong></h1>
            </div>
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
                                <img class="rounded-circle" src=../' . $ruta_imagen . ' width="65" alt="Foto de Perfil">
                                Bienvenido: <span class="fw-bold">' . $nombre_usuario . '</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../perfil.php">Mi Perfil</a></li>';
                if ($administrador == 1) {
                    echo '<li><a class="dropdown-item" href="/administracion/indexadmin.php">Panel de Control</a></li>';
                }
                echo '       
                                <form method="post">
                                    <input type="hidden" name="cerses" value="true">
                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                </form>
                            </ul>
                        </li>
                    </ul>';
            } else {
                echo '
                    <article class="ms-auto">
                        <h2 hidden>Inicio sesión</h2>
                        <form class="d-flex align-items-center" method="post">
                            <div class="">
                                <a class="btn btn-primary" href="/php/login.php">Iniciar Sesion</a>
                                <a class="btn btn-primary" href="/php/registro.php">Registrarse</a>
                            </div>
                        </form>
                    </article>';
            }
            ?>
        </div>
    </nav>
    <br>
    <article class="flex-grow-1 mx-3">
        <div class="input-with-icon">
            <button id="reload"><img src="../media/iconos/reload.png" alt="Recargar"></button>
            <input type="text" id="searchInput" placeholder="Buscar por nombre...">
        </div>
        <br>
        <table class="table table-striped mx-auto" id="categorias">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">NºProductos</th>
                    <th scope="col">Productos</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                foreach ($registros as $registro) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $registro['idCategoria'] . "</th>";
                    echo "<td>" . $registro['nombreCategoria'] . "</td>";
                    echo "<td>" . $registro['numProductos'] . "</td>";
                    echo "<td>
                            <button class='btn btn-link' type='button' data-bs-toggle='collapse' data-bs-target='#productos-" . $registro['idCategoria'] . "' aria-expanded='false' aria-controls='productos-" . $registro['idCategoria'] . "'>
                                <img src='../media/iconos/prods.png' style='width:15px'>
                            </button>
                          </td>";
                    echo "<td>
                            <form action=\"#\" method=\"post\" onsubmit=\"return confirmarEliminacion()\">
                                <input type=\"hidden\" name=\"idCategoria\" value=\"" . $registro['idCategoria'] . "\">
                                <button type=\"submit\" name=\"eliminar\"><img src=\"../media/iconos/delete.png\" style=\"width:15px\"></button>
                            </form>
                          </td>";
                    echo "</tr>";

                    // Subtabla para mostrar los productos de la categoría actual
                    echo "<tr class='collapse' id='productos-" . $registro['idCategoria'] . "'>";
                    echo "<td colspan='6'>";
                    echo "<table class='table'>";
                    echo "<thead>
                            <tr>
                                <th scope='col'>ID Producto</th>
                                <th scope='col'>Nombre Producto</th>
                                <th scope='col'>Cantidad</th>
                                <th scope='col'>Hidratos de Carbono</th>
                                <th scope='col'>Calorías</th>
                                <th scope='col'>Grasas</th>
                                <th scope='col'>Proteínas</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";

                    $productos_query = "SELECT * FROM productos WHERE idCategoriaFK = ?";
                    $productos_preparada = $conn->prepare($productos_query);
                    $productos_preparada->bind_param("i", $registro['idCategoria']);
                    $productos_preparada->execute();
                    $productos_resultado = $productos_preparada->get_result();
                    while ($producto = $productos_resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $producto['idProducto'] . "</td>";
                        echo "<td>" . $producto['nombreProducto'] . "</td>";
                        echo "<td>" . $producto['cantidadProducto'] . "</td>";
                        echo "<td>" . $producto['hcarbonoProducto'] . "</td>";
                        echo "<td>" . $producto['caloriasProducto'] . "</td>";
                        echo "<td>" . $producto['grasasProducto'] . "</td>";
                        echo "<td>" . $producto['proteinasProducto'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </article>
    <br>
    <footer class="footer bg-dark text-light p-2 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h5>Información de contacto</h5>
                    <p>Email: info@example.com</p>
                    <p>&copy; 2024 FitFood. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h5>Enlaces útiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="../index.php">Inicio</a></li>
                        <li><a href="#">Servicios</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script>
        const reload = document.getElementById("reload");

        reload.addEventListener("click", (_) => {
            // el _ es para indicar la ausencia de parametros
            location.reload();
        });
        // Función para filtrar usuarios por nombre
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#categorias tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Confirmación de eliminación
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que quieres eliminar esta categoría?");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>