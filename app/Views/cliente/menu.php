<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $categorias */ ?>
<?php /** @var array $productos */ ?>

<section class="menu-section">

    <div class="menu-header">
        <h1>Menú</h1>
        <p>Elegí tus productos favoritos</p>
    </div>

    <!-- Filtro categorias-->
    <div class="menu-categorias">

        <button class="categoria active" data-categoria="todos"> Todos </button>

        <?php foreach ($categorias as $categoria): ?>
            <button class="categoria" data-categoria="<?= esc($categoria['idCategoria']) ?>">
                <?= esc($categoria['nombre']) ?>
            </button>
        <?php endforeach; ?>    

    </div>
    <?php foreach ($categorias as $categoria): ?> 

        <?php
            $productosCategoria = array_filter(
                $productos,
                function ($producto) use ($categoria) {
                    return $producto['idCategoria'] == $categoria['idCategoria'];
                }
            );
            ?>

        <!--Seccion categorias-->
                
        <?php if (!empty($productosCategoria)): ?>

            <div class="categoria-seccion" data-seccion="<?= esc($categoria['idCategoria']) ?>">

                <h2>
                    <?= esc($categoria['nombre']) ?>
                </h2>

                <div class="productos-grid">

                    <?php foreach ($productosCategoria as $producto): ?>
                        <div class="producto-card">

                            <img src="<?= base_url('img/' . $producto['urlImagen']) ?>" alt="<?= esc($producto['nombre']) ?>">

                            <div class="producto-info">

                                <h3>
                                    <?= esc($producto['nombre']) ?>
                                </h3>

                                <span class="precio"> $<?= number_format($producto['precio'], 0, ',', '.') ?> </span>

                                <button class="btn-ver" 
                                    onclick="abrirModal(
                                    '<?= esc($producto['nombre'], 'js') ?>',
                                    '<?= esc($producto['descripcion'], 'js') ?>',
                                    <?= $producto['precio'] ?>,
                                    '<?= base_url('img/' . $producto['urlImagen']) ?>'
                                )">
                                Ver más
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>      
    <?php endforeach; ?>         
</section>
<!--Modal detalle-->
<div id="modalProducto" class="modal-producto">

    <div class="modal-contenido">

        <button class="cerrar-modal" onclick="cerrarModal()">
            &times;
        </button>

        <img id="modalImagen" src="" alt="Producto">
    
        <div class="modal-info">

            <h2 id="modalNombre"></h2>

            <p id="modalDescripcion"></p>

            <span id="modalPrecio" class="modal-precio"></span>

            <div class="cantidad">

                <span>Cantidad:</span>

                <button type="button" onclick="disminuirCantidad()">−</button>

                <span id="cantidad">1</span>

                <button type="button" onclick="aumentarCantidad()">+</button>

            </div>


            <button class="btn-agregar">
                Agregar al pedido
            </button>

        </div>

    </div>

</div>
<script>

let cantidadActual = 1;
let precioUnitario = 0;
// Abrir modal detalle
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

    const precioTotal = precioUnitario *cantidadActual;

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

</script>
<!--Filtro de categorias-->
<script>
const botonesCategoria = document.querySelectorAll(".categoria");
const seccionesCategoria = document.querySelectorAll(".categoria-seccion");

botonesCategoria.forEach(boton => {

    boton.addEventListener("click", function() {

        botonesCategoria.forEach(btn => {
            btn.classList.remove("active");
        });

        this.classList.add("active");

        const categoriaSeleccionada = this.getAttribute("data-categoria");

        seccionesCategoria.forEach(seccion => {

            const categoriaSeccion = seccion.getAttribute("data-seccion");

            if (
                categoriaSeleccionada === "todos" ||
                categoriaSeccion === categoriaSeleccionada
            ) {
                seccion.style.display = "";
            } else {
                seccion.style.display = "none";
            }

        });

    });

});

</script>

<?= $this->endSection() ?>