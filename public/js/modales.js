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
let idItemActual = null;
let incluyeBebidaActual = 0;
let incluyeEmpanadasActual = 0;

function abrirModal(idItem, nombre, descripcion, precio, imagen, incluyeBebida = 0, incluyeEmpanadas = 0) {

    idItemActual = idItem;
    incluyeBebidaActual = incluyeBebida;
    incluyeEmpanadasActual = incluyeEmpanadas;

    document.getElementById("modalNombre").textContent = nombre;
    document.getElementById("modalDescripcion").textContent = descripcion;
    document.getElementById("modalImagen").src = imagen;

    precioUnitario = precio;
    cantidadActual = 1;

    document.getElementById("cantidad").textContent = cantidadActual;

    document.getElementById("comentario").value = "";

    // Mostrar u ocultar selección de bebida
    const contenedorBebida = document.getElementById("contenedorBebida");
    const bebida = document.getElementById("bebida");

    const contenedorEmpanadas = document.getElementById("contenedorEmpanadas");
    const empanadas = document.getElementById("empanadas");
    const btnAgregarPedido = document.getElementById("btnAgregarPedido");
    
    // BEBIDA
    if (contenedorBebida && bebida) {

        if (incluyeBebida == 1) {

            contenedorBebida.style.display = "block";
            bebida.value = "";

            if (btnAgregarPedido) {
                btnAgregarPedido.disabled = true;
                btnAgregarPedido.textContent = "Falta seleccionar bebida";
            }

        } else {

            contenedorBebida.style.display = "none";
            bebida.value = "";

            if (btnAgregarPedido) {
                btnAgregarPedido.disabled = false;
                btnAgregarPedido.textContent = "Agregar al pedido";
            }
        }
    }

    // EMPANADAS
    if (contenedorEmpanadas && empanadas) {

        if (incluyeEmpanadas == 1) {

            contenedorEmpanadas.style.display = "block";
            empanadas.value = "";

        } else {

            contenedorEmpanadas.style.display = "none";
            empanadas.value = "";
        }
    }

    validarOpciones();

    actualizarPrecio();

    document.getElementById("modalProducto").style.display = "flex";
}

function validarOpciones() {

    const btnAgregarPedido = document.getElementById("btnAgregarPedido");
    if (!btnAgregarPedido) return;

    let bebidaOk = true;
    let empanadasOk = true;

    const bebida = document.getElementById("bebida");
    const empanadas = document.getElementById("empanadas");

    if (incluyeBebidaActual == 1 && bebida) {
        bebidaOk = bebida.value !== "";
    }

    if (incluyeEmpanadasActual == 1 && empanadas) {
        empanadasOk = empanadas.value !== "";
    }

    if (bebidaOk && empanadasOk) {

        btnAgregarPedido.disabled = false;
        btnAgregarPedido.textContent = "Agregar al pedido";

    } else if (!bebidaOk && !empanadasOk) {

        btnAgregarPedido.disabled = true;
        btnAgregarPedido.textContent = "Falta seleccionar bebida y empanadas";

    } else if (!bebidaOk) {

        btnAgregarPedido.disabled = true;
        btnAgregarPedido.textContent = "Falta seleccionar bebida";

    } else if (!empanadasOk) {

        btnAgregarPedido.disabled = true;
        btnAgregarPedido.textContent = "Falta seleccionar empanadas";
    }
}

const bebida = document.getElementById("bebida");

if (bebida) {
    bebida.addEventListener("change", validarOpciones);
}

const empanadas = document.getElementById("empanadas");

if (empanadas) {
    empanadas.addEventListener("change", validarOpciones);
}

// Actualizacion de botones + y - en el Detalle del pedido
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

