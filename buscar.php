<?php
include("conexion.php");
$con = conectar();

$search = isset($_POST['search']) ? $_POST['search'] : '';

if (!empty($search)) {
    $sql = "SELECT * FROM productos WHERE articulos LIKE '%$search%'";
    $query = $con->query($sql);

    while ($row = $query->fetch_assoc()) {
        echo "
        <tr>
            <td>{$row['stock']}</td>
            <td>{$row['articulos']}</td>
            <td>{$row['precio_venta']}</td>
            <td>
                <button class='btn btn-primary add-to-cart' 
                        data-id='{$row['idproducto']}' 
                        data-descripcion='{$row['articulos']}' 
                        data-precio='{$row['precio_venta']}'>
                    Agregar
                </button>
            </td>
        </tr>";
    }
}
?>
