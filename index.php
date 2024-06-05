<?php
require("php/errores.php");
require("php/funciones.php");

// CONEXION
$conn = conectarBBDD();


//-------------SELECT------------//
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitFood</title>
    <link rel="icon" href="media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg" style="background-color: #006691;">
        <div class="container-fluid">
            <a href="index.php">
                <img class="rounded" src="media/logoancho.png" alt="logo" width="155">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item m-2">
                        <a href="dietas/comidas.php">
                            <img src="media/iconos/add.png" width="65" alt="Nueva Dieta">
                        </a>
                    </li>
                    <li class="nav-item m-2">
                        <a href="prods/productos.php">
                            <img src="media/iconos/productos.png" width="65" alt="Ver productos">
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
                                <img class="rounded-circle" src=' . $ruta_imagen . ' width="65" alt="Foto de Perfil">
                                Bienvenido: <span class="fw-bold">' . $nombre_usuario . '</span>
                            </a>
                        
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>';
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
                    </ul>'
                ?>
                <?php
                } else {
                ?>
                    <article class="ms-auto">
                        <h2 hidden>Inicio sesión</h2>
                        <form class="d-flex align-items-center" method="post">
                            <div class="">
                                <a class="btn btn-primary" href="/php/login.php">Iniciar Sesion</a>
                                <a class="btn btn-primary" href="/php/registro.php">Registrarse</a>
                            </div>
                        </form>
                    </article>

                <?php
                }
                ?>

                </section>
    </nav>
    <header>
        <!-- CARRUSEL IMAGENES  -->
        <header style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" style="width: 100%; height: 100%;">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="media/general/teta1.png" class="d-block w-100 h-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="media/general/teta1.png" class="d-block w-100 h-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="media/general/teta1.png" class="d-block w-100 h-100" alt="...">
                    </div>
                </div>
            </div>
        </header>
        <h1 class="display-3 mb-3 mt-5 text-center justify-content-center">FitFood</h1>
        <div class="container p-5">
            <div class=" justify-content-center">

                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-lg-5 col-md-4 col-sm-12 text-center m-1">
                        <img src="media/general/banerLateral.jpg" width="390" alt="Baner" class="img-fluid">
                    </div>
                    <div style="background-color:#ffffff6d; line-height: 1.9;" class="rounded col-lg-5 col-md-6 col-sm-12 text-center m-1 h6">
                        <p class="rounded m-1 p-2">"En FitFood, nos dedicamos a diseñar dietas personalizadas que se adaptan a tu estilo de vida
                            y objetivos de salud y bienestar. Nuestro equipo de expertos en nutrición crea planes alimenticios
                            equilibrados y deliciosos, utilizando ingredientes frescos y nutritivos. Además, en FitFood nos
                            enorgullecemos de proporcionar información detallada sobre el valor nutricional de los alimentos
                            que recomendamos. Desde el conteo de calorías hasta el equilibrio de macronutrientes, te ofrecemos
                            la orientación y el apoyo necesarios para que tomes decisiones informadas sobre tu alimentación.
                            Ya sea que desees perder peso, ganar masa muscular o simplemente llevar una vida más saludable, en
                            FitFood te ofrecemos las herramientas que necesitas para alcanzar tus metas. ¡Únete a nuestra comunidad y
                            comienza tu viaje hacia una alimentación más consciente y satisfactoria!"</p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer bg-dark text-light p-2 clearfix">
            <div class="container">
                <div class="row m-3">
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

</body>

</html>