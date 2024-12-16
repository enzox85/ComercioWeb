<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "comercio2");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener término de búsqueda
$search = $_GET['search'] ?? '';

// Consultar productos que coinciden con el término de búsqueda
$sql = "SELECT idproducto, articulos, precio_venta FROM productos WHERE articulos LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Guardar los productos en un arreglo
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

// Devolver los productos en formato JSON
echo json_encode($productos);

$stmt->close();
$conn->close();
?>
