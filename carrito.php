<?php
// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si se recibieron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar las claves esperadas en el POST
    if (isset($_POST['id'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad'])) {
        $id = $_POST['id'];
        $descripcion = $_POST['descripcion'];
        $precio = (float)$_POST['precio'];
        $cantidad = (int)$_POST['cantidad'];

        // Buscar si el producto ya está en el carrito
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['id'] == $id) {
                $producto['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }

        // Si no está en el carrito, agregarlo
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $id,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ];
        }
    }
}

// Construir la tabla del carrito
$totalGeneral = 0;
$html = "";
foreach ($_SESSION['carrito'] as $index => $producto) {
    $subtotal = $producto['cantidad'] * $producto['precio'];
    $totalGeneral += $subtotal;
    $html .= "
        <tr>
            <td>{$producto['descripcion']}</td>
            <td>{$producto['cantidad']}</td>
            <td>{$producto['precio']}</td>
            <td>{$subtotal}</td>
            <td>
                <button class='btn btn-danger btn-sm remove-from-cart' data-index='{$index}'>Eliminar</button>
            </td>
        </tr>";
}

// Mostrar mensaje si el carrito está vacío
if (empty($html)) {
    $html = "<tr><td colspan='5'>El carrito está vacío.</td></tr>";
}

// Mostrar el total general
$html .= "
    <tr>
        <td colspan='3'><strong>Total General:</strong></td>
        <td colspan='2'><strong>{$totalGeneral}</strong></td>
    </tr>";

echo $html;
?>
