<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "comercio2");

// Manejo de errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Parámetros de paginación y búsqueda
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$limite = 50; // Máximo de productos por página
$offset = ($pagina - 1) * $limite;

// Consulta para contar el total de productos encontrados
$whereClause = $busqueda ? "WHERE articulos LIKE ?" : "";
$totalQuery = "SELECT COUNT(*) AS total FROM productos $whereClause";
$stmt = $conexion->prepare($totalQuery);

if ($busqueda) {
    $param = "%" . $busqueda . "%";
    $stmt->bind_param("s", $param);
}
$stmt->execute();
$result = $stmt->get_result();
$total = $result->fetch_assoc()['total'];
$totalPaginas = ceil($total / $limite);

// Consulta para obtener los productos con búsqueda y paginación
$query = "SELECT * FROM productos $whereClause LIMIT ?, ?";
$stmt = $conexion->prepare($query);

if ($busqueda) {
    $stmt->bind_param("sii", $param, $offset, $limite);
} else {
    $stmt->bind_param("ii", $offset, $limite);
}
$stmt->execute();
$productos = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center">Productos</h1>
    
    <!-- Formulario de búsqueda -->
    <form method="GET" action="index.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <!-- Mensaje de cantidad encontrada -->
    <?php if ($busqueda): ?>
        <p>Se encontraron <strong><?= $total ?></strong> producto(s) para "<strong><?= htmlspecialchars($busqueda) ?></strong>".</p>
    <?php endif; ?>

    <!-- Tabla de productos -->
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= $producto['idproducto'] ?></td>
                    <td><?= htmlspecialchars($producto['articulos']) ?></td>
                    <td>$<?= number_format($producto['precio_venta'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $pagina === $i ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>