<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD_PDO();

sesionN1();

// Obtener el correo electrónico del usuario actualmente conectado
$correoElectronicoUsuario = obtenerCorreoElectronicoUsuario();

// Obtener el ID del usuario correspondiente al correo electrónico
$idUsuario = obtenerIDUsuarioPorCorreo($correoElectronicoUsuario);

// Manejar la inserción del evento en la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST['title'];
    $start = $_POST['start'];
    $end = isset($_POST['end']) ? $_POST['end'] : null;
    $color = $_POST['color'];
    $idUsuario = $_POST['idUsuario'];

    // Insertar el evento en la base de datos
    $sql_insert = "INSERT INTO eventos (title, start, end, color, idUsuario) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->execute([$titulo, $start, $end, $color, $idUsuario]);

    // Notificar al cliente que el evento se ha insertado correctamente
    header("Location:../perfil.php");
} else {
    // Si no se reciben datos por POST, puedes manejarlo de acuerdo a tus necesidades
    echo "No se han recibido datos por POST";
}
