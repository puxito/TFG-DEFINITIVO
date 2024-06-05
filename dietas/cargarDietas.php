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

// Obtener la lista de eventos solo para el usuario actual
$sql_eventos = "SELECT * FROM eventos WHERE idUsuario = ?";
$stmt_eventos = $conn->prepare($sql_eventos);
$stmt_eventos->execute([$idUsuario]); // Pasa el ID del usuario como parámetro
$resultado = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);

