<?php
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    die("El carrito está vacío.");
}

$conn = new mysqli('localhost', 'root', '', 'comercio2');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

foreach ($_SESSION['carrito'] as $item) {
    $idart = $item['id'];
    $descripcion = $item['descripcion'];
    $cantidad = $item['cantidad'];
    $precio_unitario = $item['precio'];
    $total = $cantidad * $precio_unitario;
    $fecha = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO ventas (idart, articulos, cantidad, total, fecha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isids", $idart, $descripcion, $cantidad, $total, $fecha);

    if (!$stmt->execute()) {
        echo "Error al guardar: " . $stmt->error;
        exit;
    }
}

unset($_SESSION['carrito']);
echo "Venta guardada exitosamente.";
$conn->close();
?>
