// Boton de agregar al carrito
function agregarAlCarrito() {

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