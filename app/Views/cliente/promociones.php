<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $categorias */ ?>
<?php /** @var array $productos */ ?>

<section class="menu-section">

    <div class="menu-header">
        <h1>Promociones</h1>
        <p>¡Aprovechá nuestras promos!</p>
    </div>
    
    <div class="productos-grid">

        <?php foreach ($productos as $producto): ?>

            <div class="producto-card">

                <img
                    src="<?= base_url('img/' . $producto['urlImagen']) ?>"
                    alt="<?= esc($producto['nombre']) ?>">

                <div class="producto-info">

                    <h3>
                        <?= esc($producto['nombre']) ?>
                    </h3>

                    <span class="precio">
                        $<?= number_format($producto['precio'], 0, ',', '.') ?>
                    </span>

                    <button
                        class="btn-ver"
                        onclick="abrirModal(
                            <?= $producto['idItem'] ?>,
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

                <span class="cantidad-label">Cantidad:</span>

                <button type="button" onclick="disminuirCantidad()">−</button>

                <span id="cantidad" class="cantidad-numero">1</span>

                <button type="button" onclick="aumentarCantidad()">+</button>

            </div>

             <div class="comentario-producto">
                <label for="comentario">Comentario:</label>

                <textarea
                    id="comentario"
                    name="comentario"
                    placeholder="Ej.: Sin mayonesa, sin cebolla..."
                    maxlength="200">
                </textarea>
            </div>


            <button type="button" class="btn-agregar mt-3" onclick="agregarAlCarrito()">
                Agregar al pedido
            </button>

        </div>

    </div>

</div>
<!-- Modal producto agregado -->
<div class="modal fade" id="productoAgregadoModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modal-cala">

            <div class="modal-body text-center p-4">

                <i class="bi bi-check-circle-fill text-success"
                   style="font-size: 4rem;">
                </i>

                <h3 class="mt-3">¡Producto agregado!</h3>

                <p>
                    El producto fue agregado correctamente a tu pedido.
                </p>

                <button type="button"
                        class="btn boton-login mt-3"
                        data-bs-dismiss="modal">

                    Aceptar

                </button>

            </div>

        </div>

    </div>

</div>
<?= $this->endSection() ?>