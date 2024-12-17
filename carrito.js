let carrito = []; // Arreglo que guarda los productos en el carrito

// Agregar un producto al carrito
function agregarAlCarrito(id, nombre, precio) {
    const productoExistente = carrito.find((item) => item.id === id);

    if (productoExistente) {
        productoExistente.cantidad++; // Incrementar cantidad si ya existe
    } else {
        carrito.push({ id, nombre, cantidad: 1, precio }); // Agregar nuevo producto
    }

    actualizarCarrito();
}

// Eliminar un producto del carrito por índice
function eliminarDelCarrito(index) {
    carrito.splice(index, 1); // Eliminar producto del array
    actualizarCarrito();
}

// Actualizar la tabla del carrito y calcular el total
function actualizarCarrito() {
    const carritoTable = document.getElementById("carritoBody");
    carritoTable.innerHTML = ""; // Limpiar la tabla

    let totalGeneral = 0;

    carrito.forEach((producto, index) => {
        const subtotal = producto.cantidad * producto.precio;
        totalGeneral += subtotal;

        // Crear fila de producto
        const row = `
            <tr>
                <td>${escapeHTML(producto.nombre)}</td>
                <td>${producto.cantidad}</td>
                <td>${producto.precio.toFixed(2)}</td>
                <td>${subtotal.toFixed(2)}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarDelCarrito(${index})">Eliminar</button>
                </td>
            </tr>
        `;
        carritoTable.innerHTML += row;
    });

    // Mostrar el total de la venta
    document.getElementById("totalVenta").innerText = `Total: $${totalGeneral.toFixed(2)}`;
}

// Función para escapar HTML dinámico (seguridad)
function escapeHTML(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, (m) => map[m]);
}

// Buscar productos en tiempo real
function buscarProducto() {
    const searchTerm = document.getElementById("search").value.toLowerCase().trim();
    if (searchTerm === "") {
        alert("Por favor, ingresa un término de búsqueda válido.");
        return;
    }

    const productosFiltrados = productos.filter((producto) =>
        producto.nombre.toLowerCase().includes(searchTerm)
    );

    const productosTable = document.getElementById("productosTable");
    productosTable.innerHTML = ""; // Limpiar la tabla

    if (productosFiltrados.length === 0) {
        productosTable.innerHTML = `<tr><td colspan="4">No se encontraron productos.</td></tr>`;
        return;
    }

    productosFiltrados.forEach((producto) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${producto.id}</td>
            <td>${escapeHTML(producto.nombre)}</td>
            <td>${producto.precio.toFixed(2)}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="agregarAlCarrito(${producto.id}, '${escapeHTML(producto.nombre)}', ${producto.precio})">Agregar</button>
            </td>
        `;
        productosTable.appendChild(row);
    });
}

// Guardar la venta
$("#guardar-venta").on("click", function () {
    if (carrito.length === 0) {
        alert("No hay productos en el carrito.");
        return;
    }

    if (confirm("¿Deseas confirmar esta venta?")) {
        $.ajax({
            url: "guardar_venta.php",
            type: "POST",
            data: { carrito: JSON.stringify(carrito) },
            success: function (response) {
                alert("Venta guardada exitosamente.");
                carrito = []; // Vaciar el carrito después de guardar
                actualizarCarrito();
            },
            error: function () {
                alert("Error al guardar la venta.");
            }
        });
    }
});

// Cancelar la venta
$("#cancelar-venta").on("click", function () {
    if (confirm("¿Estás seguro de cancelar la venta?")) {
        carrito = []; // Vaciar el carrito localmente
        actualizarCarrito();

        $.ajax({
            url: "cancelar_venta.php",
            type: "POST",
            success: function () {
                alert("Venta cancelada.");
            },
            error: function () {
                alert("Error al cancelar la venta.");
            }
        });
    }
});

// Cargar el carrito al inicio (si existiera)
$(document).ready(function () {
    actualizarCarrito();
});
