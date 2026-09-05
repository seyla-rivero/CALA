<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $categorias */ ?>
<?php /** @var array $productos */ ?>

<section class="menu-section">

    <div class="menu-header">
        <h1>Promociones</h1>
        <p>Las mejores promociones</p>
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

<?= $this->endSection() ?>