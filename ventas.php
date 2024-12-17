<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VENTAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: #f8f9fa;
        }

        h4 {
            color: #ffd700;
        }

        .header-label {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff88;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .table {
            background-color: #ffffff;
            color: #212529;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Buscar productos
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $.ajax({
                    url: "buscar.php",
                    type: "POST",
                    data: { search: value },
                    success: function(data) {
                        $("#tabla tbody").html(data);
                        $("#count-label").text($("#tabla tbody tr").length);
                    },
                    error: function() {
                        alert("Error al buscar productos.");
                    }
                });
            });

            // Agregar producto al carrito
            $(document).on("click", ".add-to-cart", function() {
                var id = $(this).data("id");
                var descripcion = $(this).data("descripcion");
                var precio = $(this).data("precio");
                $.ajax({
                    url: "carrito.php",
                    type: "POST",
                    data: { id: id, descripcion: descripcion, precio: precio, cantidad: 1 },
                    success: function(data) {
                        $("#carrito tbody").html(data);
                    },
                    error: function() {
                        alert("Error al agregar producto al carrito.");
                    }
                });
            });

            // Guardar venta
            $("#guardar-venta").on("click", function() {
                if (confirm("¿Deseas guardar esta venta?")) {
                    $.ajax({
                        url: "guardar_venta.php",
                        type: "POST",
                        success: function(response) {
                            alert(response);
                            $("#carrito tbody").html("<tr><td colspan='5'>El carrito está vacío.</td></tr>");
                        },
                        error: function() {
                            alert("Error al guardar la venta.");
                        }
                    });
                }
            });

            // Cancelar venta
            $("#cancelar-venta").on("click", function() {
                if (confirm("¿Deseas cancelar la venta?")) {
                    $.ajax({
                        url: "cancelar_venta.php",
                        type: "POST",
                        success: function(response) {
                            $("#carrito tbody").html(response);
                            alert("Venta cancelada.");
                        },
                        error: function() {
                            alert("Error al cancelar la venta.");
                        }
                    });
                }
            });

            // Eliminar producto del carrito
            $(document).on("click", ".remove-from-cart", function() {
                var index = $(this).data("index");
                $.ajax({
                    url: "eliminar_del_carrito.php",
                    type: "POST",
                    data: { index: index },
                    success: function(data) {
                        $("#carrito tbody").html(data);
                    },
                    error: function() {
                        alert("Error al eliminar el producto.");
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container content-section">
        <div class="row header-row">
            <div>
                <label class="header-label">Productos Disponibles</label>
            </div>
            <div>
                <input type="text" id="search" class="form-control" placeholder="Buscar por nombre">
                <p class="mt-1">Productos encontrados: <span id="count-label">0</span></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table" id="tabla">
                    <thead class="table-success table-striped">
                        <tr>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos cargados dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h4>Carrito</h4>
                <table class="table" id="carrito">
                    <thead class="table-success table-striped">
                        <tr>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">El carrito está vacío.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <button class="btn btn-success" id="guardar-venta">Guardar Venta</button>
                    <button class="btn btn-danger" id="cancelar-venta">Cancelar Venta</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
