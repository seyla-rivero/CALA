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
        <div class="notificacion-cala notificacion-exito" role="status">
            <div class="notificacion-cala-icono">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="notificacion-cala-texto">
                <strong>¡Listo!</strong>
                <span><?= esc($mensaje) ?></span>
            </div>

            <button type="button"
                    class="notificacion-cala-cerrar"
                    aria-label="Cerrar notificación"
                    onclick="this.closest('.notificacion-cala').remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="notificacion-cala notificacion-error" role="alert">
            <div class="notificacion-cala-icono">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>

            <div class="notificacion-cala-texto">
                <strong>Ocurrió un problema</strong>
                <span><?= esc($error) ?></span>
            </div>

            <button type="button"
                    class="notificacion-cala-cerrar"
                    aria-label="Cerrar notificación"
                    onclick="this.closest('.notificacion-cala').remove()">
                <i class="bi bi-x-lg"></i>
            </button>
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

                    // Datos preparados en el controller.
                    $estado = strtolower(trim(
                        (string) ($pedido['estado'] ?? '')
                    ));

                    $claseEstado = $pedido['claseEstado'] ?? 'otro';

                    $esDelivery = $pedido['esDelivery'] ?? false;
                    $claseEntrega = $pedido['claseEntrega'] ?? 'retiro';

                    $claseMetodoPago = $pedido['claseMetodoPago'] ?? 'otro';
                    $clasePago = $pedido['clasePago'] ?? '';
                    $textoEstadoPago = $pedido['textoEstadoPago'] ?? 'Pendiente';

                    $fechaMostrar = $pedido['fechaMostrar'] ?? 'Fecha no disponible';
                    $nombreCompleto = $pedido['nombreCompleto'] ?? 'Cliente';

                    // Construir la URL de la imagen del producto.
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
                                Pedido #<?= esc($pedido['numeroPedido'] ?? $pedido['idPedido']) ?>
                            </h2>
                            <div class="card-cala-fecha">
                                <i class="bi bi-clock"></i>
                                <?= esc($pedido['fechaMostrar']) ?>
                            </div>
                        </div>

                        <span class="card-cala-estado <?= esc($pedido['claseEstado']) ?>">
                            <?= esc($pedido['estado'] ?? 'Sin estado') ?>
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

                                
                                <?php if (
                                    $claseMetodoPago === 'transferencia' &&
                                    $clasePago === 'pendiente'
                                ): ?>

                                    
                                        <form action="<?= base_url('admin/pedidos/verificar-pago/' .$pedido['idPedido']) ?>"
                                            method="post" class="form-cala-confirmacion" data-mensaje="¿Confirmás que recibiste la transferencia del pedido #<?= esc($pedido['numeroPedido'] ?? $pedido['idPedido'], 'attr') ?>?">

                                        <?= csrf_field() ?>

                                        <button type="submit" class="btn-cala-verificar-pago">
                                            <i class="bi bi-check-circle"></i>
                                            Confirmar pago
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <span class="card-cala-estado-pago <?= esc($clasePago) ?>">
                                        <?= esc($textoEstadoPago) ?>
                                    </span>

                                <?php endif; ?>

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

                        <?php if (!empty($pedido['accion'])): ?>
                            <?php $accion = $pedido['accion']; ?>

                            
                                <form action="<?= base_url('admin/pedidos/actualizar-estado/' .$pedido['idPedido']) ?>" method="post" class="form-cala-confirmacion" data-mensaje="¿Querés continuar con la acción: <?= esc($accion['texto'], 'attr') ?> del pedido #<?= esc($pedido['numeroPedido'] ?? $pedido['idPedido'], 'attr') ?>?">

                                <?= csrf_field() ?>

                                <input
                                    type="hidden"
                                    name="nuevoEstado"
                                    value="<?= esc($accion['estado']) ?>"
                                >

                                <button
                                    type="submit"
                                    class="<?= esc($accion['clase']) ?>"
                                >
                                    <i class="bi <?= esc($accion['icono']) ?>"></i>
                                    <?= esc($accion['texto']) ?>
                                </button>
                            </form>

                        <?php elseif (
                            str_contains($estado, 'entregado') ||
                            str_contains($estado, 'retirado') ||
                            str_contains($estado, 'finalizado')
                        ): ?>

                            <div class="card-cala-en-proceso">
                                <i class="bi bi-check-circle-fill"></i>
                                Pedido finalizado
                            </div>

                        <?php elseif (str_contains($estado, 'cancel')): ?>

                            <div class="card-cala-en-proceso">
                                <i class="bi bi-x-circle-fill"></i>
                                Pedido cancelado
                            </div>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

<!-- MODAL DE CONFIRMACIÓN CALA DELIVERY -->
<div id="modalConfirmacionCala" class="cala-modal-fondo" aria-hidden="true" hidden>

    <div class="cala-modal" role="dialog" aria-modal="true" aria-labelledby="calaModalTitulo" aria-describedby="calaModalMensaje">

        <div class="cala-modal-icono">
            <i class="bi bi-question-circle-fill"></i>
        </div>

        <h2 id="calaModalTitulo">¿Confirmar acción?</h2>

        <p id="calaModalMensaje">
            ¿Querés continuar con esta acción?
        </p>

        <div class="cala-modal-botones">
            <button type="button" id="calaModalCancelar" class="cala-modal-btn cancelar">
                Cancelar
            </button>

            <button type="button" id="calaModalAceptar" class="cala-modal-btn aceptar">
                <i class="bi bi-check-lg"></i>
                Sí, continuar
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.notificacion-cala').forEach(function (notificacion) {
        setTimeout(function () {
            if (!notificacion.isConnected) return;

            notificacion.style.opacity = '0';
            notificacion.style.transform = 'translateY(-8px)';

            setTimeout(function () {
                notificacion.remove();
            }, 300);
        }, 4000);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalConfirmacionCala');
    const mensaje = document.getElementById('calaModalMensaje');
    const botonCancelar = document.getElementById('calaModalCancelar');
    const botonAceptar = document.getElementById('calaModalAceptar');

    if (!modal || !mensaje || !botonCancelar || !botonAceptar) {
        return;
    }

    let formularioPendiente = null;
    let elementoAnterior = null;

    function cerrarModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        formularioPendiente = null;

        if (elementoAnterior && elementoAnterior.isConnected) {
            elementoAnterior.focus();
        }

        elementoAnterior = null;
    }

    document.querySelectorAll('.form-cala-confirmacion').forEach(function (formulario) {
        formulario.addEventListener('submit', function (evento) {
            // Permitir el envío cuando el administrador ya confirmó.
            if (formulario.dataset.confirmado === '1') {
                delete formulario.dataset.confirmado;
                return;
            }

            // Primera instancia: mostrar la confirmación personalizada.
            evento.preventDefault();

            formularioPendiente = formulario;
            elementoAnterior = document.activeElement;

            mensaje.textContent =
                formulario.dataset.mensaje ||
                '¿Querés continuar con esta acción?';

            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');

            botonCancelar.focus();
        });
    });

    botonCancelar.addEventListener('click', cerrarModal);

    botonAceptar.addEventListener('click', function () {
        if (!formularioPendiente) {
            cerrarModal();
            return;
        }

        const formulario = formularioPendiente;

        // Evitar que la confirmación vuelva a aparecer al enviar.
        formulario.dataset.confirmado = '1';

        cerrarModal();

        // Respeta la validación HTML y los demás eventos del formulario.
        if (typeof formulario.requestSubmit === 'function') {
            formulario.requestSubmit();
        } else {
            delete formulario.dataset.confirmado;
            formulario.submit();
        }
    });

    // Cerrar al hacer clic fuera de la tarjeta.
    modal.addEventListener('click', function (evento) {
        if (evento.target === modal) {
            cerrarModal();
        }
    });

    // Cerrar con la tecla Escape.
    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && !modal.hidden) {
            cerrarModal();
        }
    });
});
</script>

<?= $this->endSection() ?>



