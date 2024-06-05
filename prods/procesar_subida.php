<?php
session_start();
include "../php/funciones.php";
include "../php/errores.php";

sesionN1();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["imagenProducto"]) && $_FILES["imagenProducto"]["error"] === UPLOAD_ERR_OK) {
        $imagenProducto = $_FILES["imagenProducto"];

        $directorio_destino = "../media/prods/";

        $nombre_imagen = uniqid('img_') . '_' . basename($imagenProducto['name']);

        $ruta_imagen = $directorio_destino . $nombre_imagen;

        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($imagenProducto["tmp_name"], $ruta_imagen)) {
            $ruta_imagen_actualizada = $ruta_imagen;
        } else {
            // Error al mover el archivo
            header("Location: ../error.php?mensaje=" . urlencode("Error al subir la imagen."));
            exit();
        }
    } else {

        if (isset($_POST["imagen_actual"])) {
            $ruta_imagen_actualizada = $_POST["imagen_actual"];
        } else {
            $ruta_imagen_actualizada = null;
        }
    }

    // Actualizar el producto en la base de datos
    $conn = conectarBBDD();

    $idProducto = $_POST["idProducto"];

    $sql = "UPDATE productos SET nombreProducto = ?, cantidadProducto = ?, hcarbonoProducto = ?, caloriasProducto = ?, grasasProducto = ?, proteinasProducto = ?, idCategoriaFK = ?, imgProducto = ? WHERE idProducto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddddddsi", $_POST["nombreProducto"], $_POST["cantidadProducto"], $_POST["hcarbonoProducto"], $_POST["caloriasProducto"], $_POST["grasasProducto"], $_POST["proteinasProducto"], $_POST["idCategoriaFK"], $ruta_imagen_actualizada, $idProducto);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: ../cruds/crud_productos.php");
    exit();
} else {
    
    header("Location: ../error.php?mensaje=" . urlencode("Acceso no autorizado."));
    exit();
}
