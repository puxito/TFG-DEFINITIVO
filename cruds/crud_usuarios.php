<?php
// ARCHIVOS
require("../php/errores.php");
require("../php/funciones.php");

// VARIABLES
$mensaje = '';
sesionN2();
// Conexión con la BBDD
$conn = conectarBBDD();

$datosUsuario = obtenerDatosUsuario();

//-------------SELECT------------//

// Consulta para obtener usuarios y sus roles
$consultausuario = "SELECT * FROM usuarios LEFT JOIN roles ON usuarios.idRolFK = roles.idRol";
$preparada = $conn->prepare($consultausuario);
if ($preparada === false) {
    die("Error en la preparación: " . $conn->error);
}
$preparada->execute();
$resultado = $preparada->get_result();
$registros = $resultado->fetch_all(MYSQLI_ASSOC);
if ($registros === false) {
    die("Error en la ejecución: " . $conn->error);
}

// Consulta para obtener todos los roles
$consultaRoles = "SELECT * FROM roles";
$preparadaRoles = $conn->prepare($consultaRoles);
if ($preparadaRoles === false) {
    die("Error en la preparación: " . $conn->error);
}
$preparadaRoles->execute();
$resultadoRoles = $preparadaRoles->get_result();
$roles = $resultadoRoles->fetch_all(MYSQLI_ASSOC);
if ($roles === false) {
    die("Error en la ejecución: " . $conn->error);
}

//-------------DELETE------------//
if (isset($_POST['eliminar'])) {
    $idUsuario = $_POST['idUsuario'];
    $borrarusuario = "DELETE FROM usuarios WHERE idUsuario = ?";
    $preparada = $conn->prepare($borrarusuario);
    $preparada->bind_param("i", $idUsuario);
    if ($preparada->execute()) {
        $mensaje = "Usuario eliminado correctamente";
    } else {
        $mensaje = "No se ha podido eliminar el usuario";
    }
}

//-------------UPDATE------------//
if (isset($_POST["actualizar"])) {
    $idUsuario = $_POST["idUsuario"];
    $nombreUsuario = $_POST["nombreUsuario"];
    $apellidosUsuario = $_POST["apellidosUsuario"];
    $fechaNacimientoUsuario = $_POST["fechaNacimientoUsuario"];
    $correoElectronicoUsuario = $_POST["correoElectronicoUsuario"];
    $idRolFK = $_POST["idRolFK"];

    $actualizarusuario = "UPDATE usuarios SET nombreUsuario = ?, 
                                              apellidosUsuario = ?, 
                                              fechaNacimientoUsuario = ?, 
                                              correoElectronicoUsuario = ?, 
                                              idRolFK = ? 
                                              WHERE idUsuario = ?";
    $preparada = $conn->prepare($actualizarusuario);
    $preparada->bind_param("ssssii", $nombreUsuario, $apellidosUsuario, $fechaNacimientoUsuario, $correoElectronicoUsuario, $idRolFK, $idUsuario);
    if ($preparada->execute()) {
        $mensaje = "Usuario actualizado correctamente";
    } else {
        $mensaje = "No se ha podido actualizar el usuario";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../estilos/adminstyle.css">
    <style>
        .perfil {
            transition: transform 0.3s;
        }

        .perfil:hover {
            transform: scale(1.05);
        }
    </style>
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
                <h1 class="display-6 text-light text-center"><strong>Usuarios</strong></h1>
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
    <article class="mx-3">
        <div class="input-with-icon">
            <button id="reload"><img src="../media/iconos/reload.png" alt="Recargar"></button>
            <input type="text" id="searchInput" placeholder="Buscar por nombre...">
        </div>
        <h5 id="mensaje" style="text-align: center"><?php echo $mensaje; ?></h5>
        <br>
        <table class="table table-striped mx-auto" id="usuarios">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Fecha de Nacimiento</th>
                    <th scope="col">Fecha de Registro</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                foreach ($registros as $registro) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $registro['idUsuario'] . "</th>";
                    echo "<td>" . $registro['nombreUsuario'] . "</td>";
                    echo "<td>" . $registro['apellidosUsuario'] . "</td>";
                    echo "<td>" . $registro['correoElectronicoUsuario'] . "</td>";
                    echo "<td>" . $registro['fechaNacimientoUsuario'] . "</td>";
                    echo "<td>" . $registro['fechaRegistroUsuario'] . "</td>";
                    echo "<td>" . $registro['nombreRol'] . "</td>";
                    echo "<td>
                <button type=\"button\" onclick=\"toggleForm(" . $registro['idUsuario'] . ")\"><img src=\"../media/iconos/edit.png\" style=\"width:15px\"></button>
                </td>
                <td>
                <form action=\"#\" method=\"post\" onsubmit=\"return confirmarEliminacion()\">
                    <input type=\"hidden\" name=\"idUsuario\" value=\"" . $registro['idUsuario'] . "\">
                    <button type=\"submit\" name=\"eliminar\"><img src=\"../media/iconos/delete.png\" style=\"width:15px\"></button>
                </form>
                </td>";
                    echo "</tr>";

                    // Formulario de edición oculto para cada usuario
                    echo "<tr id=\"form-" . $registro['idUsuario'] . "\" style=\"display:none;\">
                <td colspan=\"9\">
                    <form action=\"#\" class=\"form\" method=\"post\">
                        <fieldset class=\"w-50 mx-auto\">
                            <input type=\"hidden\" name=\"idUsuario\" value=\"" . $registro['idUsuario'] . "\">
                            <input class=\"form-control\" type=\"text\" name=\"nombreUsuario\" value=\"" . $registro['nombreUsuario'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"text\" name=\"apellidosUsuario\" value=\"" . $registro['apellidosUsuario'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"text\" name=\"correoElectronicoUsuario\" value=\"" . $registro['correoElectronicoUsuario'] . "\">
                            <br>
                            <input class=\"form-control\" type=\"date\" name=\"fechaNacimientoUsuario\" value=\"" . $registro['fechaNacimientoUsuario'] . "\">
                            <br>
                            <select class=\"form-control\" name=\"idRolFK\" id=\"idRolFK\">";
                    foreach ($roles as $rol) {
                        $selected = ($rol['idRol'] == $registro['idRolFK']) ? "selected" : "";
                        echo "<option value=\"" . $rol['idRol'] . "\" $selected>" . $rol['nombreRol'] . "</option>";
                    }
                    echo "</select>
                            <input type=\"submit\" value=\"Actualizar\" class=\"form-control mt-2 bg-warning\" name=\"actualizar\">
                        </fieldset>
                    </form>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
    </article>
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
        // Recargar la página
        const reload = document.getElementById("reload");
        reload.addEventListener("click", (_) => {
            location.reload();
        });

        // Filtrar usuarios por nombre
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#usuarios tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que quieres eliminar este usuario?");
        }

        function toggleForm(idUsuario) {
            var form = document.getElementById('form-' + idUsuario);
            if (form.style.display === 'none') {
                form.style.display = 'table-row';
            } else {
                form.style.display = 'none';
            }
        }

        setTimeout(function() {
            document.getElementById("mensaje").style.display = "none";
        }, 2000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>