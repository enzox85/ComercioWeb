let timeout;
let carrito = []; // Array para almacenar los productos del carrito

function buscarProducto() {
    const searchValue = document.getElementById('search').value;

    if (searchValue === '') {
        const productosTable = document.getElementById('productosTable');
        productosTable.innerHTML = ''; // Limpiar tabla
        const productoCountDiv = document.getElementById('productoCount');
        productoCountDiv.style.display = 'none'; // Ocultar el contador
        return; // Salir de la función si no hay texto de búsqueda
    }

    clearTimeout(timeout); // Cancelar la búsqueda anterior

    timeout = setTimeout(() => {
        fetch(`buscar.php?search=${searchValue}`)
            .then(response => response.json())
            .then(data => {
                const productosTable = document.getElementById('productosTable');
                productosTable.innerHTML = ''; // Limpiar tabla
                const productoCountDiv = document.getElementById('productoCount');
                const productoCountText = document.getElementById('productoCountText');

                if (data.length > 0) {
                    productoCountText.textContent = `Se encontraron ${data.length} productos.`;
                    productoCountDiv.style.display = 'block';
                } else {
                    productoCountText.textContent = 'No se encontraron productos.';
                    productoCountDiv.style.display = 'block';
                }

                data.forEach(producto => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${producto.idproducto}</td>
                        <td>${producto.articulos}</td>
                        <td>$${producto.precio_venta}</td>
                        <td><button class="btn btn-primary btn-sm" onclick="agregarAlCarrito(${producto.idproducto}, '${producto.articulos}', ${producto.precio_venta})">Agregar</button></td>
                    `;
                    productosTable.appendChild(row);
                });
            })
            .catch(error => console.error('Error al buscar productos:', error));
    }, 500);
}

function agregarAlCarrito(idproducto, articulo, precio_venta) {
    const productoEnCarrito = carrito.find(item => item.idproducto === idproducto);

    if (productoEnCarrito) {
        productoEnCarrito.cantidad++;
    } else {
        carrito.push({ idproducto, articulo, precio_venta, cantidad: 1 });
    }

    actualizarCarrito();
}

function actualizarCarrito() {
    const carritoTable = document.getElementById('carritoTable');
    carritoTable.innerHTML = ''; // Limpiar tabla
    let totalVenta = 0;

    carrito.forEach((item, index) => {
        const totalItem = item.cantidad * item.precio_venta;
        totalVenta += totalItem;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.articulo}</td>
            <td><input type="number" value="${item.cantidad}" min="1" onchange="actualizarCantidad(${item.idproducto}, this.value)"></td>
            <td>$${item.precio_venta}</td>
            <td>$${totalItem.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarDelCarrito(${item.idproducto})">Eliminar</button></td>
        `;
        carritoTable.appendChild(row);
    });

    document.getElementById('totalVenta').textContent = `Total: $${totalVenta.toFixed(2)}`;
}

function actualizarCantidad(idproducto, cantidad) {
    const producto = carrito.find(item => item.idproducto === idproducto);
    if (producto) {
        producto.cantidad = parseInt(cantidad);
        actualizarCarrito();
    }
}

function eliminarDelCarrito(idproducto) {
    carrito = carrito.filter(item => item.idproducto !== idproducto);
    actualizarCarrito();
}

function guardarVenta() {
    const productos = carrito.map(item => ({
        articulos: item.articulo,
        cantidad: item.cantidad,
        idproducto: item.idproducto,
        total: item.precio_venta * item.cantidad
    }));

    const ventaData = { productos };

    fetch('guardar_venta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(ventaData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Venta guardada con éxito!');
            carrito = [];
            actualizarCarrito();
            document.getElementById('search').focus();
        } else {
            alert('Error al guardar la venta: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error al guardar la venta:', error);
    });
}
