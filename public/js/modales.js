//Se abre el modal con los errores del Login y Registro
document.addEventListener("DOMContentLoaded", function () {

    if (modalAbrir === "registro") {
        let modal = new bootstrap.Modal(
            document.getElementById("loginRegistro")
        );

        modal.show();
    }

    if (modalAbrir === "login") {
        let modal = new bootstrap.Modal(
            document.getElementById("loginModal")
        );

        modal.show();
    }

});

// Abrir modal detalle de Producto y Promocion
let cantidadActual = 1;
let precioUnitario = 0;

function abrirModal(nombre, descripcion, precio, imagen) {

    document.getElementById("modalNombre").textContent = nombre;
    document.getElementById("modalDescripcion").textContent = descripcion;
    document.getElementById("modalImagen").src = imagen;

    precioUnitario = precio;
    cantidadActual = 1;

    document.getElementById("cantidad").textContent = cantidadActual;

    actualizarPrecio();

    document.getElementById("modalProducto").style.display = "flex";
}

function actualizarPrecio() {

    const precioTotal = precioUnitario * cantidadActual;

    document.getElementById("modalPrecio").textContent = "$" + precioTotal.toLocaleString("es-AR");

}

function cerrarModal() {

    document.getElementById("modalProducto").style.display = "none";
}

function aumentarCantidad() {

    cantidadActual++;

    document.getElementById("cantidad").textContent = cantidadActual;

    actualizarPrecio();
}

function disminuirCantidad() {

    if (cantidadActual > 1) {

        cantidadActual--;

        document.getElementById("cantidad").textContent = cantidadActual;

        actualizarPrecio();
    }
}

window.addEventListener("click", function(event) {

    const modal = document.getElementById("modalProducto");

    if (event.target === modal) {
        cerrarModal();
    }

});

