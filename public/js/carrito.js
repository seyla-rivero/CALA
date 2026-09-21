// Boton de agregar al carrito
function agregarAlCarrito() {

    const comentario = document.getElementById("comentario").value.trim();
    const bebida = document.getElementById("bebida");
    const bebidaSeleccionada = bebida ? bebida.value : "";
    const empanadas = document.getElementById("empanadas");
    const empanadasSeleccionadas = empanadas ? empanadas.value : "";


    // Si la promoción incluye bebida, es obligatorio seleccionar una
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

            const modal = new bootstrap.Modal(
                document.getElementById("productoAgregadoModal")
            );

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
function aumentarCantidadCarrito(idItem) {

    fetch(urlAumentarCantidad, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            idItem: idItem
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

function disminuirCantidadCarrito(idItem) {

    fetch(urlDisminuirCantidad, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            idItem: idItem
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

            document.getElementById("contadorCarrito").textContent =
                data.cantidad;

        })
        .catch(error => {
            console.error("Error al obtener la cantidad del carrito:", error);
        });
}

document.addEventListener("DOMContentLoaded", function() {
    actualizarContadorCarrito();
});