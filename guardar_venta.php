<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comercio2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir los datos enviados en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Preparar la consulta SQL para insertar los datos de la venta
$sql = "INSERT INTO ventas (num_fact, articulos, cantidad, idart, total, cuentcor, efectivo, credito, debito, fecha) 
        VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la sentencia
$stmt = $conn->prepare($sql);

// Enlazar los parámetros
foreach ($data['productos'] as $producto) {
    $articulos = $producto['articulos'];
    $cantidad = $producto['cantidad'];
    $idart = $producto['idproducto'];
    $total = $producto['total'];
    $cuentcor = $data['cuentcor'];
    $efectivo = $data['efectivo'];
    $credito = $data['credito'];
    $debito = $data['debito'];
    $fecha = date("Y-m-d H:i:s"); // Asumiendo que se toma la fecha y hora actual

    // Enlazar los parámetros de la consulta
    $stmt->bind_param("sssss", $articulos, $cantidad, $idart, $total, $cuentcor, $efectivo, $credito, $debito, $fecha);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error al guardar: " . $stmt->error]);
        exit();
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();

// Devolver respuesta de éxito
echo json_encode(["success" => true]);
?>
