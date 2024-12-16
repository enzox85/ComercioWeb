<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta - EnSoft</title>
    <!-- Agregar Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar el CSS externo -->
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center mb-4">Punto de Venta - Ventas</h1>
        
        <div class="row">
            <!-- Buscador -->
            <div class="mb-3">
                <input type="text" id="search" class="form-control" placeholder="Buscar producto..." oninput="buscarProducto()">
            </div>

            <!-- Contador de Productos Encontrados -->
            <div id="productoCount" class="alert alert-info" style="display: none;">
                <strong id="productoCountText"></strong>
            </div>

            <!-- Tabla de Productos -->
            <div class="col-md-8">
                <h4>Productos Disponibles</h4>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="productosTable">
                        <!-- Los productos se llenarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Barra Lateral con Resumen de Venta y Botones -->
            <div class="col-md-4 sidebar">
                <div class="sale-summary">
                    <h5 id="totalVenta">Total: $0.00</h5>
                    <button class="btn btn-success btn-custom mt-3" onclick="guardarVenta()">Guardar Venta</button>
                    <button class="btn btn-warning btn-custom mt-3" onclick="cancelarVenta()">Cancelar Venta</button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Tabla de Artículos Seleccionados -->
            <div class="col-md-8">
                <h4>Artículos Seleccionados</h4>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="carritoTable">
                        <!-- Aquí los artículos seleccionados se llenarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Agregar Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Agregar el JavaScript externo -->
    <script src="script.js"></script>
</body>
</html>
