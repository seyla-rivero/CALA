<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<section class="menu-section">

    <div class="menu-header">
        <h1>Menú</h1>
        <p>Elegí tus productos favoritos</p>
    </div>

    <!-- Categorías -->
    <div class="menu-categorias">
        <button class="categoria active">Todos</button>
        <button class="categoria">Hamburguesas</button>
        <button class="categoria">Lomos</button>
        <button class="categoria">Milanesas</button>
        <button class="categoria">Pizzas</button>
        <button class="categoria">Empanadas</button>
        <button class="categoria">Panchos</button>
    </div>

    <div class="categoria-seccion">

        <h2>Hamburguesas</h2>

        <div class="productos-grid">

            <!-- CARD 1 -->
            <div class="producto-card">

                <img src="img/hamburguesa.jpg"
                     alt="Hamburguesa clásica">

                <div class="producto-info">

                    <h3>Hamburguesa Clásica</h3>

                    <span class="precio">$8.500</span>

                    <button
                        class="btn-ver"
                        onclick="abrirModal(
                            'Hamburguesa Clásica',
                            'Carne, queso, lechuga, tomate y aderezos.',
                            '$8.500',
                            'img/hamburguesa.jpg'
                        )">
                        Ver más
                    </button>

                </div>

            </div>


            <!-- CARD 2 -->
            <div class="producto-card">

                <img src="img/hamburguesa-especial.jpg"
                     alt="Hamburguesa especial">

                <div class="producto-info">

                    <h3>Hamburguesa Especial</h3>

                    <span class="precio">$9.500</span>

                    <button
                        class="btn-ver"
                        onclick="abrirModal(
                            'Hamburguesa Especial',
                            'Carne, doble queso, jamón, huevo, lechuga y tomate.',
                            '$9.500',
                            'img/hamburguesa-especial.jpg'
                        )">
                        Ver más
                    </button>

                </div>

            </div>


            <!-- CARD 3 -->
            <div class="producto-card">

                <img src="img/hamburguesa-doble.jpg"
                     alt="Hamburguesa doble">

                <div class="producto-info">

                    <h3>Hamburguesa Doble</h3>

                    <span class="precio">$10.500</span>

                    <button
                        class="btn-ver"
                        onclick="abrirModal(
                            'Hamburguesa Doble',
                            'Doble carne, doble queso y aderezos.',
                            '$10.500',
                            'img/hamburguesa-doble.jpg'
                        )">
                        Ver más
                    </button>

                </div>

            </div>

        </div>

    </div>

    <div class="categoria-seccion">

        <h2>Lomos</h2>

        <div class="productos-grid">

            <div class="producto-card">

                <img src="img/lomo.jpg"
                     alt="Lomo completo">

                <div class="producto-info">

                    <h3>Lomo Completo</h3>

                    <span class="precio">$9.000</span>

                    <button
                        class="btn-ver"
                        onclick="abrirModal(
                            'Lomo Completo',
                            'Carne de lomo, jamón, queso, lechuga, tomate y aderezos.',
                            '$9.000',
                            'img/lomo.jpg'
                        )">
                        Ver más
                    </button>

                </div>

            </div>

        </div>

    </div>

</section>


<div id="modalProducto" class="modal-producto">

    <div class="modal-contenido">

        <button class="cerrar-modal" onclick="cerrarModal()">
            &times;
        </button>

        <img id="modalImagen"
             src=""
             alt="Producto">

        <div class="modal-info">

            <h2 id="modalNombre"></h2>

            <p id="modalDescripcion"></p>

            <span id="modalPrecio" class="modal-precio"></span>


            <!-- CANTIDAD -->

            <div class="cantidad">

                <span>Cantidad:</span>

                <button onclick="disminuirCantidad()">−</button>

                <span id="cantidad">1</span>

                <button onclick="aumentarCantidad()">+</button>

            </div>


            <button class="btn-agregar">
                Agregar al pedido
            </button>

        </div>

    </div>

</div>
<script>
let cantidadActual = 1;
// Abrir modal detalle
function abrirModal(nombre, descripcion, precio, imagen) {
    document.getElementById("modalNombre").textContent = nombre;
    document.getElementById("modalDescripcion").textContent = descripcion;
    document.getElementById("modalPrecio").textContent = precio;
    document.getElementById("modalImagen").src = imagen;
    cantidadActual = 1;
    document.getElementById("cantidad").textContent = cantidadActual;
    document.getElementById("modalProducto").style.display = "flex";
}

function cerrarModal() {
    document.getElementById("modalProducto").style.display = "none";

}

function aumentarCantidad() {
    cantidadActual++;
    document.getElementById("cantidad").textContent = cantidadActual;

}

function disminuirCantidad() {

    if (cantidadActual > 1) {
        cantidadActual--;
        document.getElementById("cantidad").textContent = cantidadActual;

    }

}

window.addEventListener("click", function(event) {
    const modal = document.getElementById("modalProducto");

    if (event.target === modal) {
        cerrarModal();
    }
});
</script>

<?= $this->endSection() ?>