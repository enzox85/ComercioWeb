<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "comercio2";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, nombre, precio FROM productos WHERE articulos LIKE ?";
$stmt = $conn->prepare($sql);
$search = "%$search%";
$stmt->bind_param("s", $search);
$stmt->execute();

$result = $stmt->get_result();
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);

$stmt->close();
$conn->close();
?>
