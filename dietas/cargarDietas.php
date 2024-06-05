<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD_PDO();

sesionN1();

$correoElectronicoUsuario = obtenerCorreoElectronicoUsuario();

$idUsuario = obtenerIDUsuarioPorCorreo($correoElectronicoUsuario);

$sql_eventos = "SELECT * FROM eventos WHERE idUsuario = ?";
$stmt_eventos = $conn->prepare($sql_eventos);
$stmt_eventos->execute([$idUsuario]); // Pasa el ID del usuario como parámetro
$resultado = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);

