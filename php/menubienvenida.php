<?php
// ARCHIVOS
require("errores.php");
require("funciones.php");

// SESIÓN
sesionN2();

// CONEXIÓN
$conn = conectarBBDD();

// VARIABLES
$mensaje = '';

// Verificar si hay sesión iniciada y si correoElectronicoUsuario está definido
if (!isset($_SESSION["correoElectronicoUsuario"]) || empty($_SESSION["correoElectronicoUsuario"])) {
    // Redirigir a la página de inicio de sesión o mostrar un mensaje de error
    header("Location: ../php/login.php"); // Cambia la ruta según sea necesario
    exit(); // Finalizar el script
}

// Obtener el rol del usuario si la sesión está definida
if (isset($_SESSION["correoElectronicoUsuario"])) {
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT idRolFK FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $rol = $fila["idRolFK"];
}
?>

<?php if ($rol == 1): ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitFood</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body, html {
            height: 100%;
        }
        .content {
            min-height: calc(100vh - 76px - 56px);
        }
    </style>
</head>

<body class="d-flex flex-column h-100" style="background-color: #94e7ff;">
    <nav class="navbar navbar-expand-lg" style="background-color: #006691;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a href="../index.php">
                    <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="d-flex justify-content-center flex-grow-1">
                <h1 class="display-6 text-light text-center"><strong>Panel de Control</strong></h1>
            </div>
            <?php if (sesionN1()): ?>
                <?php
                // El usuario ha iniciado sesión
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
                ?>
                <ul class="ms-auto m-2 navbar-nav">
                    <li class="border border-dark rounded dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img class="rounded-circle" src=../<?= $ruta_imagen ?> width="65" alt="Foto de Perfil">
                            Bienvenido: <span class="fw-bold"><?= $nombre_usuario ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
                            <?php if ($administrador == 1): ?>
                                <li><a class="dropdown-item" href="/administracion/indexadmin.php">Panel de Control</a></li>
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="cerses" value="true">
                                <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                            </form>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <article class="ms-auto">
                    <h2 hidden>Inicio sesión</h2>
                    <form class="d-flex align-items-center" method="post">
                        <div class="">
                            <a class="btn btn-primary" href="/php/login.php">Iniciar Sesion</a>
                            <a class="btn btn-primary" href="/php/registro.php">Registrarse</a>
                        </div>
                    </form>
                </article>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container content flex-grow-1 d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Panel de Control</h5>
                        <p class="card-text">Acceso solo administradores.</p>
                        <a href="../administracion/indexadmin.php" class="btn btn-primary d-block mx-auto">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Sitio Web</h5>
                        <p class="card-text">Acceso General.</p>
                        <a href="../index.php" class="btn btn-primary d-block mx-auto">Acceder</a>
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

<?php endif; ?>

<?php if ($rol == 3): ?>
    <?php
    header("Location: ../index.php");
    exit();
    ?>
<?php endif; ?>
