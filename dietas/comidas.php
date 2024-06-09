<?php
require("../php/errores.php");
require("../php/funciones.php");

$conn = conectarBBDD();
sesionN1();

$correoElectronicoUsuario = obtenerCorreoElectronicoUsuario();
$idUsuario = obtenerIDUsuarioPorCorreo($correoElectronicoUsuario);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["agregar_comida"])) {
        $nombreComida = $_POST["nombre_comida"];
        agregarComida($nombreComida, $idUsuario);
    } elseif (isset($_POST["editar_comida"])) {
        $idComida = $_POST["id_comida"];
        $nombreComida = $_POST["nombre_comida"];
        actualizarComida($idComida, $nombreComida);
    } elseif (isset($_POST["eliminar_comida"])) {
        $idComida = $_POST["id_comida"];
        eliminarComida($idComida);
    }
}

$comidas = obtenerComidasPorUsuario($idUsuario);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Comidas</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <a href="comidas.php">
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
                                <img class="rounded-circle" src=../' . $ruta_imagen . ' width="65" alt="Foto de Perfil">
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
        <h1>Gestión de Comidas</h1>
        <form method="POST" action="comidas.php">
            <div class="mb-3">
                <label for="nombre_comida" class="form-label">Nombre de la Comida</label>
                <input type="text" class="form-control" id="nombre_comida" name="nombre_comida" required>
            </div>
            <button type="submit" class="btn btn-primary" name="agregar_comida">Agregar Comida</button>
        </form>
        <h2 class="mt-5">Mis Comidas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($comida = $comidas->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $comida['nombreComida'] ?></td>
                        <td>
                            <form method="POST" action="comidas.php" class="d-inline">
                                <input type="hidden" name="id_comida" value="<?= $comida['idComida'] ?>">
                                <input type="text" name="nombre_comida" value="<?= $comida['nombreComida'] ?>" required>
                                <button type="submit" class="btn btn-success" name="editar_comida">Editar</button>
                            </form>
                            <form method="POST" action="comidas.php" class="d-inline">
                                <input type="hidden" name="id_comida" value="<?= $comida['idComida'] ?>">
                                <button type="submit" class="btn btn-danger" name="eliminar_comida">Eliminar</button>
                            </form>
                            <a href="ver_comida.php?id=<?= $comida['idComida'] ?>" class="btn btn-info">Ver</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>