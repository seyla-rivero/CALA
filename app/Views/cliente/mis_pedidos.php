
<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container py-5">

    <h2 class="carrito-titulo mb-4">Mis pedidos</h2>

    <?php if (empty($pedidos)): ?>

        <div class="text-center py-5">
            <h4>Todavía no realizaste pedidos</h4>

            <p class="text-muted">
                Cuando confirmes un pedido, vas a poder consultar
                todos sus productos y su estado desde esta sección.
            </p>

            <a href="<?= base_url('menu') ?>" class="btn boton-login">
                Ver menú
            </a>
        </div>

    <?php else: ?>

        <?php foreach ($pedidos as $pedido): ?>

            <div class="card carrito-item shadow-sm mb-4">

                <div class="card-body p-3 p-md-4">

                    <!-- Encabezado del pedido -->
                    <div class="d-flex flex-wrap justify-content-between
                                align-items-center gap-2 mb-3">

                        <div>
                            <h5 class="carrito-nombre mb-1">
                                Pedido #<?= $pedido['idPedido'] ?>
                            </h5>

                            <small class="text-muted">
                                Fecha:
                                <?= !empty($pedido['fecha'])
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime($pedido['fecha'])
                                    )
                                    : 'No disponible'
                                ?>
                            </small>
                        </div>

                        <span class="badge bg-secondary">
                            <?= esc(ucfirst($pedido['estado'])) ?>
                        </span>

                    </div>

                    <hr>

                    <!-- Productos del pedido -->
                    <h6 class="mb-3">Productos encargados</h6>

                    <?php if (empty($pedido['detalles'])): ?>

                        <p class="text-muted">
                            No se encontraron productos para este pedido.
                        </p>

                    <?php else: ?>

                        <?php foreach ($pedido['detalles'] as $item): ?>

                            <?php
                                $cantidad = (int) $item['cantidad'];
                                $precioUnitario = (float) $item['precioUnitario'];
                                $subtotalItem = (float) $item['subTotal'];
                            ?>

                            <div class="row align-items-center
                                        g-3 mb-3">

                                <!-- Imagen -->
                                <div class="col-4 col-md-2 text-center">

                                    <?php if (!empty($item['urlImagen'])): ?>

                                        <img
                                            src="<?= base_url(
                                                'img/' . $item['urlImagen']
                                            ) ?>"
                                            alt="<?= esc(
                                                $item['nombre']
                                                ?? 'Producto'
                                            ) ?>"
                                            class="img-fluid rounded"
                                            style="width: 120px;
                                                   height: 100px;
                                                   object-fit: cover;"
                                        >

                                    <?php else: ?>

                                        <div class="text-muted small">
                                            Sin imagen
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <!-- Datos del producto -->
                                <div class="col-8 col-md-6">

                                    <h6 class="mb-1">
                                        <?= esc(
                                            $item['nombre']
                                            ?? 'Producto no disponible'
                                        ) ?>
                                    </h6>

                                    <p class="text-muted small mb-1">
                                        Cantidad: <?= $cantidad ?>
                                    </p>

                                    <p class="small mb-1">
                                        Precio unitario:
                                        $<?= number_format(
                                            $precioUnitario,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </p>

                                    <?php if (!empty($item['comentario'])): ?>
                                        <p class="small mb-1">
                                            <strong>Comentario:</strong>
                                            <?= esc($item['comentario']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($item['bebida'])): ?>
                                        <p class="small mb-1">
                                            <strong>Bebida:</strong>
                                            <?= esc($item['bebida']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($item['empanadas'])): ?>
                                        <p class="small mb-1">
                                            <strong>Empanadas:</strong>
                                            <?= esc($item['empanadas']) ?>
                                        </p>
                                    <?php endif; ?>

                                </div>

                                <!-- Subtotal -->
                                <div class="col-12 col-md-4 text-md-end">

                                    <strong class="carrito-subtotal">
                                        $<?= number_format(
                                            $subtotalItem,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>

                                </div>

                            </div>

                            <hr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    <!-- Entrega y pago -->
                    <div class="row g-3 mt-2">

                        <div class="col-md-6">

                            <h6>Datos de entrega</h6>

                            <p class="mb-1">
                                <strong>Modalidad:</strong>

                                <?php if ($pedido['tipoEntrega'] === 'delivery'): ?>
                                    Delivery
                                <?php elseif ($pedido['tipoEntrega'] === 'retiro'): ?>
                                    Retiro en sucursal
                                <?php else: ?>
                                    No especificada
                                <?php endif; ?>
                            </p>

                            <?php if ($pedido['tipoEntrega'] === 'delivery'): ?>

                                <p class="mb-1">
                                    <strong>Dirección:</strong>
                                    <?= esc(
                                        $pedido['direccionEntrega']
                                        ?: 'No especificada'
                                    ) ?>
                                </p>

                                <p class="mb-1">
                                    <strong>Zona:</strong>
                                    <?= esc(
                                        $pedido['nombreZona']
                                        ?? 'No disponible'
                                    ) ?>
                                </p>

                            <?php endif; ?>

                            <p class="mb-1">
                                <strong>Sucursal:</strong>
                                <?= esc(
                                    $pedido['nombreSucursal']
                                    ?? 'No disponible'
                                ) ?>
                            </p>

                        </div>

                        <div class="col-md-6">

                            <h6>Datos de pago</h6>

                            <p class="mb-1">
                                <strong>Método:</strong>

                                <?php if ($pedido['metodoPago'] === 'efectivo'): ?>
                                    Efectivo
                                <?php elseif ($pedido['metodoPago'] === 'transferencia'): ?>
                                    Transferencia
                                <?php else: ?>
                                    No especificado
                                <?php endif; ?>
                            </p>

                            <?php if ($pedido['metodoPago'] === 'transferencia'): ?>

                                <p class="mb-1">
                                    <strong>Estado del pago:</strong>
                                    <?= esc(
                                        ucfirst(
                                            $pedido['estadoPago']
                                            ?? 'pendiente'
                                        )
                                    ) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                    <!-- Resumen de importes -->
                    <div class="card carrito-total mt-4">
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>

                                <span>
                                    $<?= number_format(
                                        (float) $pedido['subTotal'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Costo de envío</span>

                                <span>
                                    $<?= number_format(
                                        (float) $pedido['costoEnvio'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between
                                        align-items-center">

                                <h5 class="mb-0">Total del pedido</h5>

                                <h5 class="carrito-subtotal mb-0">
                                    $<?= number_format(
                                        (float) $pedido['total'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </h5>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
