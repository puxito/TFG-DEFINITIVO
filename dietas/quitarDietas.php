<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD_PDO();

// Verificar si hay una sesión iniciada
sesionN1();

// Obtener el correo electrónico del usuario actualmente conectado
$correoElectronicoUsuario = obtenerCorreoElectronicoUsuario();

// Obtener el ID del usuario correspondiente al correo electrónico
$idUsuario = obtenerIDUsuarioPorCorreo($correoElectronicoUsuario);

// Manejar la eliminación del evento en la base de datos
if (isset($_POST['id'])) {
    // Obtener los datos del formulario
    $eventoID = $_POST['id'];

    // Validar que el ID del evento y el ID del usuario son enteros
    $eventoID = (int)$eventoID;
    $idUsuario = (int)$idUsuario;

    // Eliminar el evento de la base de datos
    $sql_delete = "DELETE FROM eventos WHERE id = ? AND idUsuario = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if ($stmt_delete->execute([$eventoID, $idUsuario])) {
        echo 'success'; // Respuesta para éxito
    } else {
        echo 'error'; // Respuesta para error
    }
} else {
    echo "No se han recibido datos por POST";
}
