<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD();

sesionN0();

// Obtener la lista de categorías
$sql_categorias = "SELECT idCategoria, nombreCategoria FROM categorias";
$stmt_categorias = $conn->prepare($sql_categorias);
$stmt_categorias->execute();
$resultado_categorias = $stmt_categorias->get_result();
$categorias = $resultado_categorias->fetch_all(MYSQLI_ASSOC);

// Verificar si se solicita ver favoritos
$verFavoritos = isset($_GET['favoritos']) && $_GET['favoritos'] == 'true';

// Si el usuario quiere ver sus favoritos
if ($verFavoritos) {
    $idUsuario = obtenerIDUsuario();
    $consultaprod = "SELECT p.*
                     FROM productos p
                     JOIN favoritos f ON p.idProducto = f.idProductoFK
                     WHERE f.idUsuarioFK = ?";
    $preparada = $conn->prepare($consultaprod);

    // Control en la preparación
    if ($preparada === false) {
        die("Error en la preparación: " . $conn->error);
    }

    // Vincular el parámetro de ID de usuario
    $preparada->bind_param("i", $idUsuario);
} else {
    // Filtrar productos por categoría si se selecciona una categoría
    $consulta_condicional = "";
    if (isset($_GET['categoria'])) {
        $categoria_seleccionada = $_GET['categoria'];
        if ($categoria_seleccionada != 'all') {
            $consulta_condicional = " WHERE idCategoriaFK = ?";
        }
    }

    // Sacar la consulta
    $consultaprod = "SELECT * FROM productos" . $consulta_condicional;
    $preparada = $conn->prepare($consultaprod);

    // Control en la preparación
    if ($preparada === false) {
        die("Error en la preparación: " . $conn->error);
    }

    // Vincular parámetros si hay una categoría seleccionada
    if (isset($categoria_seleccionada) && $categoria_seleccionada != 'all') {
        $preparada->bind_param("i", $categoria_seleccionada);
    }
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

// Obtener los productos favoritos del usuario
$idUsuario = obtenerIDUsuario();
$sql_favoritos = "SELECT idProductoFK FROM favoritos WHERE idUsuarioFK = ?";
$stmt_favoritos = $conn->prepare($sql_favoritos);
$stmt_favoritos->bind_param("i", $idUsuario);
$stmt_favoritos->execute();
$resultado_favoritos = $stmt_favoritos->get_result();
$favoritos = $resultado_favoritos->fetch_all(MYSQLI_ASSOC);
$favoritos = array_column($favoritos, 'idProductoFK');

//-------FAVORITOS-------//
if (isset($_POST['toggleFavorito'])) {
    $idProducto = $_POST['idProducto'];
    $idUsuario = obtenerIDUsuario();


    $sql_verificar = "SELECT * FROM favoritos WHERE idUsuarioFK = ? AND idProductoFK = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("ii", $idUsuario, $idProducto);
    $stmt_verificar->execute();
    $resultado_verificar = $stmt_verificar->get_result();

    if ($resultado_verificar->num_rows > 0) {

        $sql_eliminar = "DELETE FROM favoritos WHERE idUsuarioFK = ? AND idProductoFK = ?";
        $stmt_eliminar = $conn->prepare($sql_eliminar);
        $stmt_eliminar->bind_param("ii", $idUsuario, $idProducto);
        if ($stmt_eliminar->execute()) {
            echo json_encode(['action' => 'removed', 'message' => 'Producto eliminado de favoritos']);
        } else {
            echo json_encode(['error' => 'Error al eliminar el producto de favoritos']);
        }
    } else {
        // El producto no está en favoritos, agregarlo
        $sql_agregar = "INSERT INTO favoritos (idUsuarioFK, idProductoFK) VALUES (?, ?)";
        $stmt_agregar = $conn->prepare($sql_agregar);
        $stmt_agregar->bind_param("ii", $idUsuario, $idProducto);
        if ($stmt_agregar->execute()) {
            echo json_encode(['action' => 'added', 'message' => 'Producto agregado a favoritos']);
        } else {
            echo json_encode(['error' => 'Error al agregar el producto a favoritos']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/prodstyle.css">
    <title>Productos</title>
</head>

<body>
    <nav class="navbar navbar-expand-xl fixed-top" style="background-color: #006691;">
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

    <!-- Menú de categorías -->
    <div class="categorias">
        <h3><b>Categorías</b></h3>
        <ul>
            <br>
            <?php foreach ($categorias as $categoria) : ?>
                <li><img src="../media/categ/<?php echo $categoria['idCategoria']; ?>.png" alt="..."><a href="?categoria=<?php echo $categoria['idCategoria']; ?>"><?php echo $categoria['nombreCategoria']; ?></a></li>
            <?php endforeach; ?>
            <br>
        </ul>
        <hr>
        <ul>
            <li><img src="../media/categ/todos.png" alt="..."><a href="?categoria=all">Mostrar Todos</a></li>
            <li><a href="?favoritos=true">Ver Favoritos</a></li>
        </ul>
    </div>

    <div class="container">
    <div id="producto" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach ($registros as $registro) : ?>
            <div class="col">
                <div class="card">
                    <div class="card-shadow-sm">
                        <img src="<?php echo $registro['imgProducto']; ?>" class="card-img-top" alt="Imagen de <?php echo $registro['nombreProducto']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $registro['nombreProducto']; ?></h5>
                            <div class="button-container">
                                <button class="btn btn-primary detalle-btn" data-producto-id="<?php echo $registro['idProducto']; ?>" data-bs-toggle="modal" data-bs-target="#detallesModal">Detalles</button>
                                <form class="favorite-form" data-producto-id="<?php echo $registro['idProducto']; ?>">
                                    <button type="submit" class="btn btn-primary fav-btn">
                                        <img src="../media/iconos/<?php echo in_array($registro['idProducto'], $favoritos) ? 'remfav' : 'addfav'; ?>.png" alt="<?php echo in_array($registro['idProducto'], $favoritos) ? 'Eliminar favorito' : 'Agregar favorito'; ?>">
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
    <!-- Modal de Detalles -->
    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detallesModalBody">
                    <!-- Aquí se cargarán dinámicamente los detalles del producto mediante AJAX -->
                </div>
            </div>
        </div>
    </div>

    <br>
    <footer>
        <p>&copy; 2024 FitFood. Todos los derechos reservados.</p>
    </footer>

    <script>
        $(document).ready(function() {
            $(".favorite-form").on("submit", function(event) {
                event.preventDefault();
                var idProducto = $(this).data("producto-id");
                $.ajax({
                    url: "productos.php", // Cambia esto por la ruta real
                    type: "POST",
                    data: {
                        toggleFavorito: true,
                        idProducto: idProducto
                    },
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        if (response.action === 'added') {
                            // Cambia el icono o estilo para reflejar que es favorito
                            $(this).find("img").attr("src", "../media/iconos/remfav.png");
                        } else if (response.action === 'removed') {
                            // Cambia el icono o estilo para reflejar que ya no es favorito
                            $(this).find("img").attr("src", "../media/iconos/addfav.png");
                        }
                    }.bind(this),
                    error: function(xhr, status, error) {
                        alert("Error al cambiar el estado del favorito");
                    }
                });
            });
        });
        $(document).ready(function() {
            // Manejar clic en el botón "Detalles"
            $(".detalle-btn").on("click", function(event) {
                event.preventDefault();
                var idProducto = $(this).data("producto-id");
                // Realizar una solicitud AJAX para obtener los detalles del producto
                $.ajax({
                    url: "detalles_producto.php", // Ruta al script que obtiene los detalles del producto
                    type: "GET",
                    data: {
                        idProducto: idProducto
                    },
                    dataType: 'html',
                    success: function(response) {
                        // Insertar los detalles del producto en el cuerpo del modal
                        $("#detallesModalBody").html(response);
                        // Mostrar el modal
                        $("#detallesModal").modal("show");
                    },
                    error: function(xhr, status, error) {
                        alert("Error al cargar los detalles del producto");
                    }
                });
            });

            // Manejar el cierre del modal para limpiar el contenido
            $('#detallesModal').on('hidden.bs.modal', function(e) {
                $("#detallesModalBody").html("");
            });
        });
    </script>

</body>

</html>