<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var float|int $total */ ?>

<div class="container py-5">

    <h2 class="mb-4">Mi pedido</h2>

    <?php if (empty($carrito)): ?>
 
        <div class="text-center py-5">

            <h4>Tu pedido está vacío</h4>

            <p class="text-muted">
                Agregá productos desde nuestro menú.
            </p>

            <a href="<?= base_url('menu') ?>" class="btn boton-login">
                Ver menú
            </a>
        </div>

    <?php else: ?>

        <?php foreach ($carrito as $item): ?>

            <?php
                $subtotal = $item['precio'] * $item['cantidad'];
            ?>

            <div class="card carrito-item mb-3 shadow-sm">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col-md-2 text-center">

                            <img src="<?= base_url('img/' . $item['urlImagen']) ?>" 
                            alt="<?= esc($item['nombre']) ?>" class="carrito-imagen">

                        </div>

                        <div class="col-md-4">

                            <h5 class="carrito-nombre">
                                <?= esc($item['nombre']) ?>
                            </h5>

                            <p class="carrito-precio mb-1">
                                $<?= number_format($item['precio'], 2, ',', '.') ?>
                            </p>

                            <?php if (!empty($item['comentario'])): ?>

                                <small class="carrito-comentario d-block">
                                    <strong>Comentario:</strong>
                                    <?= esc($item['comentario']) ?>
                                </small>

                            <?php endif; ?>

                        </div>

                        <div class="col-md-3 text-center">

                            <div class="carrito-cantidad">

                                <button type="button" class="btn btn-outline-secondary" onclick="disminuirCantidadCarrito(<?= $item['idItem'] ?>)">−
                                </button>

                                <span>
                                    <?= $item['cantidad'] ?>
                                </span>

                                <button type="button" class="btn btn-outline-secondary" onclick="aumentarCantidadCarrito(<?= $item['idItem'] ?>)">+
                                </button>

                            </div>

                        </div>

                        <div class="col-md-3 text-end">

                            <strong class="carrito-subtotal">
                                $<?= number_format($subtotal, 2, ',', '.') ?>
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

        <div class="card carrito-total shadow-sm mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">
                        Total
                    </h4>

                    <h4 class="mb-0">
                        $<?= number_format($total, 2, ',', '.') ?>
                    </h4>

                </div>

            </div>

        </div>
        
        <div class="d-flex justify-content-between mt-4">

            <a
                href="<?= base_url('menu') ?>"
                class="btn carrito-seguir">
                Seguir comprando
            </a>

            <button
                type="button"
                class="btn carrito-continuar">
                Continuar pedido
            </button>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>