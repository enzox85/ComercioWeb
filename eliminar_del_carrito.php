<?php
// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que el índice del producto se envió correctamente
if (isset($_POST['index'])) {
    $index = $_POST['index'];

    // Eliminar el producto del carrito
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el arreglo
    }
}

// Incluir la lógica para reconstruir el carrito
include "carrito.php";
?>
