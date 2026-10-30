// Sucursal cerrada
function mostrarModalSucursalCerrada() {
    const elemento = document.getElementById("sucursalCerradaModal");

    if (!elemento) {
        alert("En este momento las sucursales están cerradas. Volvé durante el horario de atención.");
        return;
    }

    const elementoAnterior = document.activeElement;
    const modal = bootstrap.Modal.getOrCreateInstance(elemento);

    // Registrar la corrección una sola vez.
    if (!elemento.dataset.correccionFoco) {
        elemento.addEventListener("hide.bs.modal", function () {
            // Quitar el foco antes de que Bootstrap oculte el modal.
            if (elemento.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });

        elemento.dataset.correccionFoco = "true";
    }

    // Devolver el foco cuando el modal ya terminó de cerrarse.
    elemento.addEventListener("hidden.bs.modal", function devolverFoco() {
        if (
            elementoAnterior &&
            elementoAnterior.isConnected &&
            typeof elementoAnterior.focus === "function"
        ) {
            elementoAnterior.focus();
        }
    }, { once: true });

    modal.show();
}

// Boton de agregar al carrito
function agregarAlCarrito() {

    /*if (!comprasHabilitadas) {
        mostrarModalSucursalCerrada();
        return;
    }*/

    const comentario = document.getElementById("comentario").value.trim();
    const bebida = document.getElementById("bebida");
    const bebidaSeleccionada = bebida ? bebida.value : "";
    const empanadas = document.getElementById("empanadas");
    const empanadasSeleccionadas = empanadas ? empanadas.value : "";

    if (incluyeBebidaActual == 1 && bebidaSeleccionada === "") {

        return;
    }

    if (incluyeEmpanadasActual == 1 && empanadasSeleccionadas === "") {
        return;
    }

    fetch(urlAgregarCarrito, {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({
            idItem: idItemActual,
            cantidad: cantidadActual,
            comentario: comentario,
            bebida: bebidaSeleccionada,
            empanadas: empanadasSeleccionadas
        })

    })
    .then(response => response.json())

    .then(data => {

       if (data.ok) {

            cerrarModal();

            document.getElementById("comentario").value = "";

            actualizarContadorCarrito();

            const elementoModal = document.getElementById("productoAgregadoModal");

            const modal = bootstrap.Modal.getOrCreateInstance(elementoModal);

            modal.show();

        } else if (data.login) {

            cerrarModal();

            const modalLogin = new bootstrap.Modal(
                document.getElementById("loginModal")
            );

            modalLogin.show();

        } else if (data.cerrado) {
            mostrarModalSucursalCerrada();
        } else {
            alert(data.mensaje);
        }

    })

    .catch(error => {

        console.error(error);

        alert("Ocurrió un error al agregar el producto.");

    });
}

// Actualizacion de botones de + y - en el carrito del pedido
function aumentarCantidadCarrito(idDetallePedido) {

    fetch(urlAumentarCantidad, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            idDetallePedido: idDetallePedido
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.ok) {
            location.reload();
        } else {
            alert(data.mensaje || "No se pudo actualizar la cantidad.");
        }

    })
    .catch(error => {
        console.error(error);
        alert("Ocurrió un error.");
    });
}

function disminuirCantidadCarrito(idDetallePedido) {

    fetch(urlDisminuirCantidad, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            idDetallePedido: idDetallePedido
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.ok) {
            location.reload();
        } else {
            alert(data.mensaje || "No se pudo actualizar la cantidad.");
        }

    })
    .catch(error => {
        console.error(error);
        alert("Ocurrió un error.");
    });
}

function actualizarContadorCarrito() {

    fetch(urlCantidadCarrito)
        .then(response => response.json())
        .then(data => {

            console.log("Cantidad recibida:", data);

            const contador = document.getElementById("contadorCarrito");

            if (contador) {
                contador.textContent = data.cantidad ?? 0;
            }

        })
        .catch(error => {
            console.error("Error al obtener la cantidad:", error);
        });
}

document.addEventListener("DOMContentLoaded", function() {
    actualizarContadorCarrito();
});