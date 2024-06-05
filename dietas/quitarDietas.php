<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD_PDO();

// Verificar si hay una sesión iniciada
sesionN1();


$correoElectronicoUsuario = obtenerCorreoElectronicoUsuario();


$idUsuario = obtenerIDUsuarioPorCorreo($correoElectronicoUsuario);

// Manejar la eliminación del evento en la base de datos
if (isset($_POST['id'])) {

    $eventoID = $_POST['id'];


    $eventoID = (int)$eventoID;
    $idUsuario = (int)$idUsuario;


    $sql_delete = "DELETE FROM eventos WHERE id = ? AND idUsuario = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if ($stmt_delete->execute([$eventoID, $idUsuario])) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo "No se han recibido datos por POST";
}
