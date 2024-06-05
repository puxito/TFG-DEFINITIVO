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
// Sacar la consulta
$consultaproductos = "SELECT productos.*, categorias.nombreCategoria FROM productos LEFT JOIN categorias ON productos.idCategoriaFK = categorias.idCategoria";
$preparada = $conn->prepare($consultaproductos);

// Control en la preparación
if ($preparada === false) {
    die("Error en la preparación: " . $conn->error);
}

// Ejecutar la consulta
$preparada->execute();

// Obtener los resultados
$resultado = $preparada->get_result();
$registros = $resultado->fetch_all(MYSQLI_ASSOC);

// Control en la ejecución
if ($registros === false) {
    die("Error en la ejecución: " . $conn->error);
}

// Obtener todas las categorías
$consultacategorias = "SELECT * FROM categorias";
$resultadocategorias = $conn->query($consultacategorias);
$categorias = $resultadocategorias->fetch_all(MYSQLI_ASSOC);

//-------------DELETE------------//
if (isset($_POST['eliminar'])) {
    $idProducto = $_POST['idProducto'];

    $borrarproducto = "DELETE FROM productos WHERE idProducto =?";

    $preparada = $conn->prepare($borrarproducto);
    $preparada->bind_param("i", $idProducto);

    if ($preparada->execute()) {
        $mensaje = "Producto eliminado correctamente";
    } else {
        $mensaje = "No se ha podido eliminar el producto";
    }
}

//-------------UPDATE------------//
if (isset($_POST["actualizar"])) {
    $idProducto = $_POST['idProducto'];
    $nombreProducto = $_POST['nombreProducto'];
    $cantidadProducto = $_POST['cantidadProducto'];
    $hcarbonoProducto = $_POST['hcarbonoProducto'];
    $caloriasProducto = $_POST['caloriasProducto'];
    $grasasProducto = $_POST['grasasProducto'];
    $proteinasProducto = $_POST['proteinasProducto'];
    $idCategoriaFK = $_POST['idCategoriaFK'];

    // Consulta para actualizar los datos
    $actualizarproducto = "UPDATE productos SET nombreProducto =?,
                                                cantidadProducto=?,
                                                hcarbonoProducto=?,
                                                caloriasProducto=?,
                                                grasasProducto=?,
                                                proteinasProducto=?,
                                                idCategoriaFK=?
                                                WHERE idProducto =?";

    $preparada = $conn->prepare($actualizarproducto);
    $preparada->bind_param("siiddddd", $nombreProducto, $cantidadProducto, $hcarbonoProducto, $caloriasProducto, $grasasProducto, $proteinasProducto, $idCategoriaFK, $idProducto);

    if ($preparada->execute()) {
        $mensaje = "Producto actualizado correctamente";
    } else {
        $mensaje = "No se ha podido actualizar el producto";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../estilos/adminstyle.css">
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color: #006691;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <a href="../index.php">
                <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
            </a>
        </div>
        <div class="d-flex justify-content-center flex-grow-1">
            <h1 class="display-6 text-light text-center"><strong>Productos</strong></h1>
        </div>
        <?php
        if (sesionN0()) {
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
<article class="mx-3">
    <div class="input-with-icon">
        <button id="reload"><img src="../media/iconos/reload.png" alt="Recargar"></button>
        <input type="text" id="searchInput" placeholder="Buscar por nombre...">
    </div>
    <h5 id="mensaje" style="text-align: center"><?php echo $mensaje; ?></h5>
    <br>
    <table class="table table-striped mx-auto" id="productos">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Cantidad</th>
                <th scope="col">H.Carbono</th>
                <th scope="col">Calorías</th>
                <th scope="col">Grasas</th>
                <th scope="col">Proteinas</th>
                <th scope="col">Categoria</th>
                <th scope="col">Editar</th>
                <th scope="col">Eliminar</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
            foreach ($registros as $registro) {
                echo "<tr>";
                echo "<th scope='row'>" . $registro['idProducto'] . "</th>";
                echo "<td>" . $registro['nombreProducto'] . "</td>";
                echo "<td>" . $registro['cantidadProducto'] . "</td>";
                echo "<td>" . $registro['hcarbonoProducto'] . "</td>";
                echo "<td>" . $registro['caloriasProducto'] . "</td>";
                echo "<td>" . $registro['grasasProducto'] . "</td>";
                echo "<td>" . $registro['proteinasProducto'] . "</td>";
                echo "<td>" . $registro['nombreCategoria'] . "</td>";
                echo "<td>
                <button type=\"button\" onclick=\"toggleForm(" . $registro['idProducto'] . ")\"><img src=\"../media/iconos/edit.png\" style=\"width:15px\"></button>
                        </td>
                        <td>
                        <form action=\"#\" method=\"post\" onsubmit=\"return confirmarEliminacion()\">
                            <input type=\"hidden\" name=\"idProducto\" value=\"" . $registro['idProducto'] . "\">
                            <button type=\"submit\" name=\"eliminar\"><img src=\"../media/iconos/delete.png\" style=\"width:15px\"></button>
                        </form>
                        </td>";
                echo "</tr>";

                echo "<tr id=\"form-" . $registro['idProducto'] . "\" style=\"display:none;\">
                <td colspan=\"9\">
                    <form action=\"../prods/procesar_subida.php\" class=\"form\" method=\"post\" enctype=\"multipart/form-data\">
                        <fieldset class=\"w-50 mx-auto\">
                            <input type=\"hidden\" name=\"idProducto\" value=\"" . $registro['idProducto'] . "\">
                            <input class=\"form-control\" type=\"text\" name=\"nombreProducto\" value=\"" . $registro['nombreProducto'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"number\" step=\".01\" name=\"cantidadProducto\" value=\"" . $registro['cantidadProducto'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"number\" step=\".01\" name=\"hcarbonoProducto\" value=\"" . $registro['hcarbonoProducto'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"number\" step=\".01\" name=\"caloriasProducto\" value=\"" . $registro['caloriasProducto'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"number\" step=\".01\" name=\"grasasProducto\" value=\"" . $registro['grasasProducto'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"number\" step=\".01\" name=\"proteinasProducto\" value=\"" . $registro['proteinasProducto'] . "\">
                            <br>
                            <select class=\"form-control\" name=\"idCategoriaFK\" required>";
                            foreach ($categorias as $categoria) {
                                $selected = $categoria['idCategoria'] == $registro['idCategoriaFK'] ? "selected" : "";
                                echo "<option value=\"" . $categoria['idCategoria'] . "\" $selected>" . $categoria['nombreCategoria'] . "</option>";
                            }
                            echo "</select>
                            <br>
                            <label for=\"imagenProducto\">Imagen del Producto:</label>
                            <input type=\"file\" class=\"form-control\" name=\"imagenProducto\">
                            <br>
                            <input type=\"submit\" value=\"Actualizar\" class=\"form-control bg-warning\" name=\"actualizar\">
                        </fieldset>
                    </form>
                </td>
            </tr>";
            }
            ?>
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
        location.reload();
    });
    // Función para filtrar productos por nombre
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#productos tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function confirmarEliminacion() {
        return confirm("¿Estás seguro de que quieres eliminar este producto?");
    }
    setTimeout(function() {
        document.getElementById("mensaje").style.display = "none";
    }, 2000);

    function toggleForm(idProducto) {
        var form = document.getElementById('form-' + idProducto);
        if (form.style.display === 'none') {
            form.style.display = 'table-row';
        } else {
            form.style.display = 'none';
        }
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
