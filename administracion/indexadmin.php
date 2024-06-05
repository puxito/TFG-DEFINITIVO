<?php

// ARCHIVOS
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD();

// VARIABLES
$mensaje = '';

sesionN2();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <title>FitFood</title>
    <style>
        body, html {
            height: 100%;
        }
        .content {
            min-height: calc(100vh - 76px - 56px); /* Altura de nav y footer */
        }
    </style>
</head>

<body class="d-flex flex-column h-100" style="background-color:  #94e7ff;">
    <nav class="navbar navbar-expand-lg" style="background-color: #006691;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a href="../index.php">
                    <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
                </a>
            </div>
            <div class="d-flex justify-content-center flex-grow-1">
                <h1 class="display-6 text-light text-center"><strong>Panel de Control</strong></h1>
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
    <div class="container content flex-grow-1 d-flex align-items-center">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold">Administración de Usuarios</h5>
                        <p class="card-text text-center">Gestionar los usuarios del sitio web.</p>
                        <a href="../cruds/crud_usuarios.php" class="btn btn-primary d-block mx-auto">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold">Añadir Producto</h5>
                        <p class="card-text text-center">Nuevo producto para la base de datos.</p>
                        <a href="../prods/nuevoprod.php" class="btn btn-primary d-block mx-auto">Nuevo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold">Gestión de Productos</h5>
                        <p class="card-text text-center">Administrar los productos de la base de datos.</p>
                        <a href="../cruds/crud_productos.php" class="btn btn-primary d-block mx-auto">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold">Administración de Categorías</h5>
                        <p class="card-text text-center">Gestionar las categorías de los productos.</p>
                        <a href="../cruds/crud_categorias.php" class="btn btn-primary d-block mx-auto">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto bg-dark text-light p-2">
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
                        <li><a href="#">Inicio</a></li>
                        <li><a href="#">Servicios</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
