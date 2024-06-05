<?php
// ARCHIVOS
require("../php/errores.php");
require("../php/funciones.php");

// VARIABLES
$mensaje = '';
sesionN2();

// Conexión con la BBDD
$conn = conectarBBDD();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombreProducto = $_POST['nombreProducto'];
    $cantidadProducto = $_POST['cantidadProducto'];
    $hcarbonoProducto = $_POST['hcarbonoProducto'];
    $caloriasProducto = $_POST['caloriasProducto'];
    $grasasProducto = $_POST['grasasProducto'];
    $proteinasProducto = $_POST['proteinasProducto'];
    $idCategoriaFK = $_POST['idCategoriaFK'];

    // Manejo de la imagen
    $imgProducto = $_FILES['imgProducto']['name'];
    $imgProductoTmp = $_FILES['imgProducto']['tmp_name'];
    $targetDir = "../media/prods/";
    $targetFile = $targetDir . basename($imgProducto);

    // Validar y mover la imagen
    if (move_uploaded_file($imgProductoTmp, $targetFile)) {
        // Insertar datos en la base de datos
        $sql = "INSERT INTO productos (nombreProducto, cantidadProducto, hcarbonoProducto, caloriasProducto, grasasProducto, proteinasProducto, imgProducto, idCategoriaFK) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddddssi", $nombreProducto, $cantidadProducto, $hcarbonoProducto, $caloriasProducto, $grasasProducto, $proteinasProducto, $targetFile, $idCategoriaFK);

        if ($stmt->execute()) {
            $mensaje = "Producto agregado correctamente.";
        } else {
            $mensaje = "Error al agregar el producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $mensaje = "Error al cargar la imagen.";
    }
    $conn->close();
}

header("Location: nuevoprod.php?mensaje=" . urlencode($mensaje));
exit();
