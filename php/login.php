<?php
require("errores.php");
require("funciones.php");

// Sesiones
session_start();

// VARIABLES
$mensaje = '';

// Conexión con la BBDD
$conn = conectarBBDD();

$mensajesesion = inicioSesion($conn);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | FitFood</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #94e7ff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #006691;">
        <div class="container-fluid">
            <a href="../index.php">
                <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
            </a>
        </div>
    </nav>
    <br>
    <br>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Iniciar sesión</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="#">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img src="../media/iconos/usuario.png" alt="Correo electrónico" width="20">
                                    </span>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Ingrese su correo electrónico">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img src="../media/iconos/contrasena.jpg" alt="Contraseña" width="20">
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese su contraseña">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="registro.php" class="btn btn-link">Registrarse</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $mensajesesion; ?>

    <footer class="footer bg-dark text-light p-2 fixed-bottom">
        <div class="container text-center">
            <p>&copy; 2024 FitFood. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>