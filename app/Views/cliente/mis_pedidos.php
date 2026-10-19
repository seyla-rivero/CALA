
<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container pedidos-container">

    <h2 class="pedidos-titulo">
        Mis pedidos
    </h2>

    <?php if (empty($pedidos)): ?>

        <div class="pedidos-vacio">

            <i class="bi bi-bag-heart pedidos-vacio-icono"></i>

            <h4>Todavía no realizaste pedidos</h4>

            <p>
                Cuando confirmes un pedido, vas a poder consultar
                todos sus productos y su estado desde esta sección.
            </p>

            <a href="<?= base_url('menu') ?>"
               class="pedidos-boton-menu">

                <i class="bi bi-shop me-1"></i>
                Ver menú

            </a>

        </div>

    <?php else: ?>

        <?php foreach ($pedidos as $pedido): ?>

            <?php
            $estadoOriginal = trim($pedido['estado'] ?? '');
            $estado = strtolower($estadoOriginal);

            $estadosPreparacion = [
                'en preparación',
                'en preparacion',
                'preparación',
                'preparacion',
                'preparando'
            ];

            $estadosEntrega = [
                'en entrega',
                'en camino',
                'en reparto'
            ];

            if ($estado === 'pendiente') {
                $claseEstado = 'pedidos-estado-pendiente';
            } elseif (in_array($estado, $estadosPreparacion, true)) {
                $claseEstado = 'pedidos-estado-preparacion';
            } elseif (in_array($estado, $estadosEntrega, true)) {
                $claseEstado = 'pedidos-estado-entrega';
            } elseif ($estado === 'entregado') {
                $claseEstado = 'pedidos-estado-entregado';
            } elseif ($estado === 'cancelado') {
                $claseEstado = 'pedidos-estado-cancelado';
            } else {
                $claseEstado = 'pedidos-estado-otro';
            }

            $tipoEntrega = strtolower($pedido['tipoEntrega'] ?? '');
            $metodoPago = strtolower($pedido['metodoPago'] ?? '');
            ?>

            <div class="card pedidos-card mb-4">

                <div class="card-body p-3 p-md-4">

                    <!-- Encabezado del pedido -->
                    <div class="d-flex flex-wrap justify-content-between
                                align-items-center gap-3 mb-3">

                        <div>
                            <h5 class="pedidos-numero mb-1">
                                Pedido #<?= esc($pedido['idPedido']) ?>
                            </h5>

                            <div class="pedidos-fecha">
                                <i class="bi bi-calendar3 me-1"></i>

                                Fecha:
                                <?= !empty($pedido['fecha'])
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime($pedido['fecha'])
                                    )
                                    : 'No disponible'
                                ?>
                            </div>
                        </div>

                        <span class="pedidos-estado <?= $claseEstado ?>">
                            <?= esc(ucfirst($estadoOriginal ?: 'Pendiente')) ?>
                        </span>

                    </div>

                    <hr class="pedidos-separador">

                    <!-- Productos del pedido -->
                    <h6 class="pedidos-seccion-titulo">
                        <i class="bi bi-bag-heart-fill"></i>
                        Productos encargados
                    </h6>

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

                            <div class="row align-items-center g-3
                                        pedidos-producto mb-2">

                                <!-- Imagen -->
                                <div class="col-4 col-md-2 text-center">

                                    <?php if (!empty($item['urlImagen'])): ?>

                                        <img
                                            src="<?= base_url(
                                                'img/' . $item['urlImagen']
                                            ) ?>"
                                            alt="<?= esc(
                                                $item['nombre'] ?? 'Producto'
                                            ) ?>"
                                            class="img-fluid pedidos-producto-imagen"
                                        >

                                    <?php else: ?>

                                        <div class="text-muted small">
                                            <i class="bi bi-image"></i>
                                            Sin imagen
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <!-- Datos del producto -->
                                <div class="col-8 col-md-6">

                                    <h6 class="pedidos-producto-nombre mb-2">
                                        <?= esc(
                                            $item['nombre']
                                            ?? 'Producto no disponible'
                                        ) ?>
                                    </h6>

                                    <p class="pedidos-producto-info mb-1">
                                        <i class="bi bi-basket2 me-1"></i>
                                        Cantidad: <?= $cantidad ?>
                                    </p>

                                    <p class="pedidos-producto-info mb-1">
                                        Precio unitario:
                                        $<?= number_format(
                                            $precioUnitario,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </p>

                                    <?php if (!empty($item['comentario'])): ?>

                                        <p class="pedidos-producto-comentario mb-1">
                                            <strong>Comentario:</strong>
                                            <?= esc($item['comentario']) ?>
                                        </p>

                                    <?php endif; ?>

                                    <?php if (!empty($item['bebida'])): ?>

                                        <p class="pedidos-producto-comentario mb-1">
                                            <strong>Bebida:</strong>
                                            <?= esc($item['bebida']) ?>
                                        </p>

                                    <?php endif; ?>

                                    <?php if (!empty($item['empanadas'])): ?>

                                        <p class="pedidos-producto-comentario mb-1">
                                            <strong>Empanadas:</strong>
                                            <?= esc($item['empanadas']) ?>
                                        </p>

                                    <?php endif; ?>

                                </div>

                                <!-- Subtotal del producto -->
                                <div class="col-12 col-md-4 text-md-end">

                                    <strong class="pedidos-precio">
                                        $<?= number_format(
                                            $subtotalItem,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>

                                </div>

                            </div>

                            <hr class="pedidos-separador">

                        <?php endforeach; ?>

                    <?php endif; ?>

                    <!-- Datos de entrega y pago -->
                    <div class="row g-4 mt-2">

                        <!-- Entrega -->
                        <div class="col-md-6 pedidos-datos">

                            <h6 class="pedidos-seccion-titulo">
                                <i class="bi bi-truck"></i>
                                Datos de entrega
                            </h6>

                            <p class="mb-2">
                                <strong>Modalidad:</strong>

                                <?php if ($tipoEntrega === 'delivery'): ?>
                                    Delivery
                                <?php elseif ($tipoEntrega === 'retiro'): ?>
                                    Retiro en sucursal
                                <?php else: ?>
                                    No especificada
                                <?php endif; ?>
                            </p>

                            <?php if ($tipoEntrega === 'delivery'): ?>

                                <p class="mb-2">
                                    <strong>Dirección:</strong>
                                    <?= esc(
                                        $pedido['direccionEntrega']
                                        ?: 'No especificada'
                                    ) ?>
                                </p>

                                <p class="mb-2">
                                    <strong>Zona:</strong>
                                    <?= esc(
                                        $pedido['nombreZona']
                                        ?? 'No disponible'
                                    ) ?>
                                </p>

                            <?php endif; ?>

                            <p class="mb-2">
                                <strong>Sucursal:</strong>
                                <?= esc(
                                    $pedido['nombreSucursal']
                                    ?? 'No disponible'
                                ) ?>
                            </p>

                        </div>

                        <!-- Pago -->
                        <div class="col-md-6 pedidos-datos">

                            <h6 class="pedidos-seccion-titulo">
                                <i class="bi bi-credit-card"></i>
                                Datos de pago
                            </h6>

                            <p class="mb-2">
                                <strong>Método:</strong>

                                <?php if ($metodoPago === 'efectivo'): ?>
                                    Efectivo
                                <?php elseif ($metodoPago === 'transferencia'): ?>
                                    Transferencia
                                <?php else: ?>
                                    No especificado
                                <?php endif; ?>
                            </p>

                            <?php if ($metodoPago === 'transferencia'): ?>

                                <p class="mb-2">
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
                    <div class="card pedidos-resumen">

                        <div class="card-body">

                            <div class="d-flex justify-content-between
                                        align-items-center mb-3
                                        pedidos-resumen-fila">

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

                            <div class="d-flex justify-content-between
                                        align-items-center mb-3
                                        pedidos-resumen-fila">

                                <span>
                                    <i class="bi bi-truck me-1"></i>
                                    Costo de envío
                                </span>

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
                                        align-items-center gap-3">

                                <h5 class="pedidos-total-etiqueta mb-0">
                                    Total del pedido
                                </h5>

                                <h5 class="pedidos-total mb-0 text-end">
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
