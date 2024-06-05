<?php
// ARCHIVOS
require("../php/errores.php");
require("../php/funciones.php");

// VARIABLES
$mensaje = '';
sesionN2();
// Conexión con la BBDD
$conn = conectarBBDD();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #006691;
            color: #fff;
            padding: 10px 0;
        }
    </style>
</head>
<body style="background-color:  #94e7ff;">
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
                    </ul>';
            } else {
                echo '
                    <article class="ms-auto">
                        <h2 hidden>Inicio sesión</h2>
                        <form class="d-flex align-items-center" method="post">
                            <div class="">
                                <a class="btn btn-primary" href="../php/login.php">Iniciar Sesion</a>
                                <a class="btn btn-primary" href="../php/registro.php">Registrarse</a>
                            </div>
                        </form>
                    </article>';
            }
            ?>
        </div>
    </nav>
    <br>
    <div class="container">
        <h2 class="text-center mb-4">Agregar Nuevo Producto</h2>
        <form action="procesar_nuevoprod.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombreProducto">Nombre del Producto:</label>
                        <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                    </div>
                    <div class="form-group">
                        <label for="cantidadProducto">Cantidad:</label>
                        <input type="number" step=".01" class="form-control" id="cantidadProducto" name="cantidadProducto" required>
                    </div>
                    <div class="form-group">
                        <label for="hcarbonoProducto">Hidratos de Carbono:</label>
                        <input type="number" step=".01" class="form-control" id="hcarbonoProducto" name="hcarbonoProducto" required>
                    </div>
                    <div class="form-group">
                        <label for="caloriasProducto">Calorías:</label>
                        <input type="number" step=".01" class="form-control" id="caloriasProducto" name="caloriasProducto" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grasasProducto">Grasas:</label>
                        <input type="number" step=".01" class="form-control" id="grasasProducto" name="grasasProducto" required>
                    </div>
                    <div class="form-group">
                        <label for="proteinasProducto">Proteínas:</label>
                        <input type="number" step=".01" class="form-control" id="proteinasProducto" name="proteinasProducto" required>
                    </div>
                    <div class="form-group">
                        <label for="imgProducto">Imagen del Producto:</label>
                        <input type="file" class="form-control" id="imgProducto" name="imgProducto" required accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="idCategoriaFK">Categoría:</label>
                        <select class="form-control" id="idCategoriaFK" name="idCategoriaFK" required>
                            <?php
                            $query = "SELECT * FROM categorias";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['idCategoria'] . '">' . $row['nombreCategoria'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </div>
        </form>
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
                        <li><a href="../index.php">Inicio</a></li>
                        <li><a href="#">Servicios</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Incluye Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
