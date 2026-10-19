<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $pedidos */ ?>

<div class="pedidos-cala">

    <div class="encabezado-cala-pedidos">
        <div>
            <h1>
                <i class="bi bi-bag-heart-fill" style="color:#ff2f7d;"></i>
                Pedidos recibidos
            </h1>
            <p>Todos los detalles de tus pedidos, en un solo lugar.</p>
        </div>

        <div class="contador-cala-pedidos">
            <i class="bi bi-receipt"></i>
            <?= count($pedidos) ?>
            <?= count($pedidos) === 1 ? 'pedido' : 'pedidos' ?>
        </div>
    </div>

    <?php if ($mensaje = session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <?= esc($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid-cala-pedidos">

        <?php if (empty($pedidos)): ?>

            <div class="card-cala-vacio">
                <i class="bi bi-bag-x"></i>
                <h3>No hay pedidos todavía</h3>
                <p>Los pedidos de tu sucursal aparecerán aquí.</p>
            </div>

        <?php else: ?>

            <?php foreach ($pedidos as $pedido): ?>
                <?php
                    $detalles = $pedido['detalles'] ?? [];

                    $estado = strtolower(trim(
                        (string) ($pedido['estado'] ?? '')
                    ));

                    $claseEstado = 'otro';

                    if (str_contains($estado, 'pendiente')) {
                        $claseEstado = 'pendiente';
                    } elseif (str_contains($estado, 'prepar')) {
                        $claseEstado = 'preparacion';
                    } elseif (
                        str_contains($estado, 'camino') ||
                        str_contains($estado, 'reparto')
                    ) {
                        $claseEstado = 'camino';
                    } elseif (
                        str_contains($estado, 'entregado') ||
                        str_contains($estado, 'retirado') ||
                        str_contains($estado, 'finalizado')
                    ) {
                        $claseEstado = 'finalizado';
                    } elseif (str_contains($estado, 'cancel')) {
                        $claseEstado = 'cancelado';
                    }

                    $esPendiente = in_array($estado, [
                        'pendiente',
                        'pendientes'
                    ], true);

                    $tipoEntrega = strtolower(trim(
                        (string) ($pedido['tipoEntrega'] ?? '')
                    ));

                    $esDelivery = str_contains($tipoEntrega, 'delivery');

                    $claseEntrega = $esDelivery ? 'delivery' : 'retiro';

                    $metodoPago = strtolower(trim(
                        (string) ($pedido['metodoPago'] ?? '')
                    ));

                    $claseMetodoPago = 'otro';

                    if (str_contains($metodoPago, 'efectivo')) {
                        $claseMetodoPago = 'efectivo';
                    } elseif (str_contains($metodoPago, 'transferencia')) {
                        $claseMetodoPago = 'transferencia';
                    }

                    // Determinar el texto y el color del estado del pago.
                    
                    $estadoPago = strtolower(trim(
                        (string) ($pedido['estadoPago'] ?? '')
                    ));

                    $clasePago = '';
                    $textoEstadoPago = '';

                    if (str_contains($estadoPago, 'rechaz')) {
                        $clasePago = 'rechazado';
                        $textoEstadoPago = 'Rechazado';

                    } elseif (str_contains($estadoPago, 'verific')) {
                        $clasePago = 'verificado';
                        $textoEstadoPago = 'Verificado';

                    } elseif (
                        str_contains($estadoPago, 'aprob') ||
                        str_contains($estadoPago, 'pagado') ||
                        str_contains($estadoPago, 'confirmado')
                    ) {
                        $clasePago = 'aprobado';
                        $textoEstadoPago = 'Pagado';

                    } elseif (str_contains($metodoPago, 'efectivo')) {
                        $textoEstadoPago = 'A cobrar';

                    } elseif (str_contains($metodoPago, 'transferencia')) {
                        $clasePago = 'pendiente';
                        $textoEstadoPago = 'Por verificar';

                    } else {
                        $textoEstadoPago = 'Pendiente';
                    }

                    $fechaMostrar = 'Fecha no disponible';

                    if (!empty($pedido['fecha'])) {
                        $timestamp = strtotime($pedido['fecha']);

                        if ($timestamp !== false) {
                            $fechaMostrar = date(
                                'd/m/Y · H:i',
                                $timestamp
                            );
                        }
                    }

                    $nombreCompleto = trim(
                        ($pedido['nombreCliente'] ?? '') . ' ' .
                        ($pedido['apellidoCliente'] ?? '')
                    );

                    $nombreCompleto = $nombreCompleto ?: 'Cliente';

                    $crearUrlImagen = static function ($imagen) {
                        if (empty($imagen)) {
                            return null;
                        }

                        if (preg_match('#^https?://#i', $imagen)) {
                            return $imagen;
                        }

                        $imagen = ltrim($imagen, '/');

                        if (strpos($imagen, 'img/') !== 0) {
                            $imagen = 'img/' . $imagen;
                        }

                        return base_url($imagen);
                    };
                ?>

                <article class="card-cala-pedido">

                    <div class="card-cala-cabecera">
                        <div>
                            <h2 class="card-cala-numero">
                                Pedido #<?= esc($pedido['idPedido']) ?>
                            </h2>
                            <div class="card-cala-fecha">
                                <i class="bi bi-clock"></i>
                                <?= esc($fechaMostrar) ?>
                            </div>
                        </div>

                        <span class="card-cala-estado <?= esc($claseEstado) ?>">
                            <?= esc(ucfirst($pedido['estado'] ?? 'Sin estado')) ?>
                        </span>
                    </div>

                    <div class="card-cala-contenido">

                        <!-- CLIENTE -->
                        <div class="card-cala-cliente">

                            <div class="card-cala-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div class="card-cala-cliente-info">
                                <strong><?= esc($nombreCompleto) ?></strong>

                                <?php if (!empty($pedido['telefonoCliente'])): ?>
                                    <a href="tel:<?= esc($pedido['telefonoCliente']) ?>">
                                        <i class="bi bi-telephone-fill"></i>
                                        <?= esc($pedido['telefonoCliente']) ?>
                                    </a>
                                <?php else: ?>
                                    <small>Teléfono no disponible</small>
                                <?php endif; ?>

                                <?php if (!empty($pedido['emailCliente'])): ?>
                                    <small class="card-cala-email">
                                        <i class="bi bi-envelope"></i>
                                        <?= esc($pedido['emailCliente']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>

                        </div>

                        <hr class="card-cala-separador">

                        <!-- LISTADO DE PRODUCTOS -->

                        <?php foreach ($detalles as $detalle): ?>

                            <?php
                                $imagenProducto = $detalle['imagenProducto'] ?? null;
                                $urlImagen = $crearUrlImagen($imagenProducto);
                            ?>

                            <div class="card-cala-producto">

                                <div class="card-cala-producto-imagen">
                                    <?php if ($urlImagen): ?>
                                        <img
                                            src="<?= esc($urlImagen) ?>"
                                            alt="<?= esc($detalle['nombreProducto'] ?? 'Producto') ?>"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >

                                        <div class="card-cala-producto-placeholder"
                                            style="display:none;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="card-cala-producto-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-cala-producto-nombre">

                                    <strong>
                                        <?= esc($detalle['cantidad'] ?? 1) ?> ×
                                        <?= esc($detalle['nombreProducto'] ?? 'Producto') ?>
                                    </strong>

                                    <?php if (!empty(trim($detalle['comentario'] ?? ''))): ?>
                                        <div class="card-cala-detalle card-cala-comentario">
                                            <i class="bi bi-chat-left-text-fill"></i>
                                            <div>
                                                <strong>Comentario</strong>
                                                <span><?= esc($detalle['comentario']) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (
                                        !empty($detalle['bebida']) &&
                                        $detalle['bebida'] !== '0'
                                    ): ?>
                                        <div class="card-cala-detalle card-cala-bebida">
                                            <i class="bi bi-cup-straw"></i>
                                            <div>
                                                <strong>Bebida</strong>
                                                <span><?= esc($detalle['bebida']) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (
                                        !empty($detalle['empanadas']) &&
                                        $detalle['empanadas'] !== '0'
                                    ): ?>
                                        <div class="card-cala-detalle card-cala-empanadas">
                                            <i class="bi bi-egg-fried"></i>
                                            <div>
                                                <strong>Empanadas</strong>
                                                <span><?= esc($detalle['empanadas']) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                </div>

                                <span class="card-cala-precio">
                                    $<?= number_format(
                                        (float) ($detalle['subTotal'] ?? 0),
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </span>

                            </div>

                        <?php endforeach; ?>

                        <hr class="card-cala-separador">

                        <!-- ENTREGA -->
                       <div class="card-cala-entrega <?= esc($claseEntrega) ?>">
                            <i class="bi <?= $esDelivery
                                ? 'bi-bicycle'
                                : 'bi-shop' ?>"></i>

                            <div>
                                <strong>
                                    <?= $esDelivery
                                        ? 'Delivery'
                                        : 'Retiro en sucursal' ?>
                                </strong>

                                <?php if ($esDelivery): ?>

                                    <span>
                                        <?= esc(
                                            $pedido['direccionEntrega']
                                            ?? 'Dirección no especificada'
                                        ) ?>
                                    </span>

                                    <span>
                                        Zona:
                                        <?= esc(
                                            $pedido['nombreZona']
                                            ?? 'No especificada'
                                        ) ?>
                                    </span>

                                <?php else: ?>

                                    <span>
                                        <?= esc(
                                            $pedido['direccionEntrega']
                                            ?? 'Retiro en sucursal'
                                        ) ?>
                                    </span>

                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- PAGO -->
                        <div class="card-cala-pago">

                            <div class="card-cala-pago-linea">
                                <span class="card-cala-metodo-pago <?= esc($claseMetodoPago) ?>">
                                    <i class="bi <?= $claseMetodoPago === 'efectivo'
                                        ? 'bi-cash-coin'
                                        : ($claseMetodoPago === 'transferencia'
                                            ? 'bi-bank'
                                            : 'bi-wallet2') ?>"></i>

                                    <?= esc($pedido['metodoPago'] ?? 'Pago') ?>
                                </span>

                                <span class="card-cala-estado-pago <?= esc($clasePago) ?>">
                                    <?= esc($textoEstadoPago) ?>
                                </span>
                            </div>

                            <div class="card-cala-pago-linea">
                                <span>Subtotal</span>
                                <strong>
                                    $<?= number_format(
                                        (float) $pedido['subTotal'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>
                            </div>

                            <div class="card-cala-pago-linea">
                                <span>Envío</span>
                                <strong>
                                    $<?= number_format(
                                        (float) $pedido['costoEnvio'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>
                            </div>

                            <div class="card-cala-total">
                                <span>Total</span>
                                <strong>
                                    $<?= number_format(
                                        (float) $pedido['total'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>
                            </div>

                        </div>

                    </div>

                    <!-- ACCION DEL ADMINISTRADOR -->
                    <div class="card-cala-acciones">

                        <?php if ($esPendiente): ?>

                            <form
                                action="<?= base_url(
                                    'admin/pedidos/actualizar-estado/' .
                                    $pedido['idPedido']
                                ) ?>"
                                method="post"
                                onsubmit="return confirm('¿Querés pasar este pedido a preparación?');"
                            >
                                <?= csrf_field() ?>

                                <button type="submit" class="btn-cala-preparar">
                                    <i class="bi bi-fire"></i>
                                    Pasar a preparación
                                </button>
                            </form>

                        <?php elseif (str_contains($estado, 'prepar')): ?>

                            <div class="card-cala-en-proceso">
                                <i class="bi bi-fire"></i>
                                Pedido en preparación
                            </div>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>
