<?php
session_start();
include "php/funciones.php";
include "php/errores.php";


sesionN1();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagen"])) {
    $imagen = $_FILES["imagen"];


    $directorio_destino = "users/";


    $nombre_imagen = uniqid('img_') . '_' . $imagen['name'];


    $ruta_imagen = $directorio_destino . $nombre_imagen;

    if (move_uploaded_file($imagen["tmp_name"], $ruta_imagen)) {

        $conn = conectarBBDD();
        $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
        $sql = "UPDATE usuarios SET imagenUsuario = ? WHERE correoElectronicoUsuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $ruta_imagen, $correoElectronicoUsuario);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: perfil.php");
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se ha enviado ninguna imagen.";
}
