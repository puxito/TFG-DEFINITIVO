<?php
require("../php/errores.php");
require("../php/funciones.php");

// CONEXION
$conn = conectarBBDD();
// Verificar si se recibió el ID del producto
if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];

    // Aquí deberías realizar la consulta a la base de datos para obtener los detalles del producto
    // Esto es solo un ejemplo, debes adaptarlo a tu estructura de base de datos
    $consulta = "SELECT * FROM productos WHERE idProducto = ?";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró el producto
    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();

        // Generar el HTML con los detalles del producto
        $html = '<div class="container">';
        $html .= '<div class="row">';
        $html .= '<div class="col">';
        $html .= '<img src="' . $producto['imgProducto'] . '" class="img-fluid" alt="' . $producto['nombreProducto'] . '">';
        $html .= '</div>';
        $html .= '<div class="col">';
        $html .= '<h4>' . $producto['nombreProducto'] . '</h4>';
        $html .= '<p><strong>Hidratos:</strong> ' . $producto['hcarbonoProducto'] . '</p>';
        $html .= '<p><strong>Calorías:</strong> ' . $producto['caloriasProducto'] . '</p>';
        $html .= '<p><strong>Grasas:</strong> ' . $producto['grasasProducto'] . '</p>';
        $html .= '<p><strong>Proteínas:</strong> ' . $producto['proteinasProducto'] . '</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        echo $html;
    } else {
        echo "Producto no encontrado";
    }
} else {
    echo "ID de producto no proporcionado";
}
