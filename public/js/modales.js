//Se abre el modal con los errores
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
