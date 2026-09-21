<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var float|int $total */ ?>
<?php /** @var array $carrito */ ?>
<?php /** @var array $zonas */ ?>
<?php /** @var array $sucursales */ ?>

<div class="container py-5">

    <h2 class="checkout-titulo">Confirmar pedido</h2>

    <div class="row g-4">

        <div class="col-lg-7">

            <div class="checkout-card">

                <h4>¿Cómo querés recibir tu pedido?</h4>

                <div class="opciones-entrega">

                    <label class="opcion-entrega">

                        <input
                            type="radio"
                            name="tipoEntrega"
                            value="retiro"
                            checked
                        >

                        <div>
                            <i class="fa-solid fa-store"></i>

                            <strong>Retiro en sucursal</strong>

                            <small>
                                Retirá tu pedido en una de nuestras sucursales.
                            </small>
                        </div>

                    </label>

                    <label class="opcion-entrega">

                        <input
                            type="radio"
                            name="tipoEntrega"
                            value="delivery"
                        >

                        <div>
                            <i class="fa-solid fa-motorcycle"></i>

                            <strong>Delivery</strong>

                            <small>
                                Recibí tu pedido en tu domicilio.
                            </small>
                        </div>

                    </label>

                </div>

            </div>

            <div class="checkout-card">

                <h4>Datos de entrega</h4>

                <div id="datosRetiro">

                    <label class="checkout-label">
                        Seleccioná la sucursal
                    </label>

                    <select
                        id="sucursalRetiro"
                        class="form-select checkout-select"
                    >
                        <option value="" selected>
                            Seleccionar sucursal
                        </option>

                        <?php foreach ($sucursales as $sucursal): ?>
                            <option
                                value="<?= $sucursal['idSucursal'] ?>"
                                data-direccion="<?= esc($sucursal['direccion'], 'attr') ?>"
                                data-alias="<?= esc($sucursal['alias'], 'attr') ?>"
                                data-cbu="<?= esc($sucursal['cbu'], 'attr') ?>"
                                data-titular="<?= esc($sucursal['titular'], 'attr') ?>"
                            >
                                <?= esc($sucursal['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="mt-3" id="direccionRetiro" style="display: none;">
                        <label class="checkout-label">
                            Dirección de la sucursal
                        </label>

                        <p id="direccionSucursal">
                            -
                        </p>
                    </div>

                </div>

                <div id="datosDelivery" style="display: none;">

                    <label class="checkout-label">
                        Dirección de entrega
                    </label>

                    <input
                        type="text"
                        class="form-control checkout-input"
                        placeholder="Ingresá tu dirección"
                    >

                    <label class="checkout-label mt-3">
                        Zona de cobertura
                    </label>

                    <select
                        id="zonaDelivery"
                        class="form-select checkout-select"
                    >
                        <option value="" selected>
                            Seleccionar zona
                        </option>

                        <?php foreach ($zonas as $zona): ?>

                            <?php
                            $sucursalZona = null;

                            foreach ($sucursales as $sucursal) {
                                if ($sucursal['idSucursal'] == $zona['idSucursal']) {
                                    $sucursalZona = $sucursal;
                                    break;
                                }
                            }
                            ?>

                            <option
                                value="<?= $zona['idZona'] ?>"
                                data-sucursal="<?= $zona['idSucursal'] ?>"
                                data-nombre-sucursal="<?= esc($sucursalZona['nombre'], 'attr') ?>"
                                data-direccion="<?= esc($sucursalZona['direccion'], 'attr') ?>"
                                data-alias="<?= esc($sucursalZona['alias'], 'attr') ?>"
                                data-cbu="<?= esc($sucursalZona['cbu'], 'attr') ?>"
                                data-titular="<?= esc($sucursalZona['titular'], 'attr') ?>"
                                data-tarifa="<?= $zona['costoEnvio'] ?>"
                            >
                                <?= esc($zona['nombre']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div class="mt-3">

                        <label class="checkout-label">
                            Sucursal asignada
                        </label>

                        <p id="sucursalAsignada">
                            -
                        </p>

                    </div>

                    <div class="mt-3">

                        <label class="checkout-label">
                            Costo de envío
                        </label>

                        <p id="costoEnvio">
                            -
                        </p>

                    </div>

                    <div class="mt-3" id="datosTransferenciaDelivery" style="display: none;">

                        <label class="checkout-label">
                            Datos para realizar la transferencia
                        </label>

                        <div class="transferencia-info">

                            <p>
                                <strong>Titular:</strong>
                                <span id="titularTransferenciaDelivery">-</span>
                            </p>

                            <p>
                                <strong>Alias:</strong>
                                <span id="aliasTransferenciaDelivery">-</span>
                            </p>

                            <p>
                                <strong>CBU:</strong>
                                <span id="cbuTransferenciaDelivery">-</span>
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="checkout-card">

                <h4>¿Cómo querés pagar?</h4>

                <div class="opciones-pago">

                    <label class="opcion-pago">

                        <input
                            type="radio"
                            name="metodoPago"
                            value="efectivo"
                            checked
                        >

                        <i class="fa-solid fa-money-bill"></i>

                        <span>Efectivo</span>

                    </label>

                    <label class="opcion-pago">

                        <input
                            type="radio"
                            name="metodoPago"
                            value="transferencia"
                        >

                        <i class="fa-solid fa-building-columns"></i>

                        <span>Transferencia</span>

                    </label>

                </div>

                <div id="mensajeTransferencia" style="display: none;" class="mt-3">

                    <p>
                        Realizá la transferencia antes de confirmar el pedido.
                    </p>

                    <div class="transferencia-info">

                        <p>
                            <strong>Titular:</strong>
                            <span id="titularTransferencia">-</span>
                        </p>

                        <p>
                            <strong>Alias:</strong>
                            <span id="aliasTransferencia">-</span>
                        </p>

                        <p>
                            <strong>CBU:</strong>
                            <span id="cbuTransferencia">-</span>
                        </p>

                        <p class="mb-0">
                            Una vez realizada la transferencia, confirmá tu pedido.
                            El pago quedará pendiente de verificación.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-5">

            <div class="checkout-card resumen-pedido">

                <h4>Resumen del pedido</h4>

                <?php foreach ($carrito as $item): ?>

                    <div class="resumen-item">

                        <div class="resumen-producto">

                            <img
                                src="<?= base_url('img/' . $item['urlImagen']) ?>"
                                alt="<?= esc($item['nombre']) ?>"
                            >

                            <div>

                                <strong>
                                    <?= esc($item['nombre']) ?>
                                </strong>

                                <small>
                                    Cantidad: <?= $item['cantidad'] ?>
                                </small>

                            </div>

                        </div>

                        <span>
                            $<?= number_format($item['subTotal'],2,',','.') ?>
                        </span>

                    </div>

                <?php endforeach; ?>

                <hr>

                <div class="resumen-linea">

                    <span>Subtotal</span>

                    <strong>
                        $<?= number_format($total, 2, ',', '.') ?>
                    </strong>

                </div>

                <div class="resumen-linea">
                    <span>Costo de envío</span>

                    <strong id="costoEnvioResumen">
                        $0,00
                    </strong>
                </div>

                <hr>

                <div class="resumen-total">

                    <span>Total</span>

                    <strong id="totalPedido">
                        $<?= number_format($total, 2, ',', '.') ?>
                    </strong>

                </div>

                <button
                    type="button"
                    class="btn checkout-confirmar"
                    id="btnConfirmarPedido"
                >
                    Confirmar pedido
                </button>

                <a
                    href="<?= base_url('carrito') ?>"
                    class="btn checkout-volver"
                >
                    Volver al carrito
                </a>

            </div>

        </div>

    </div>

</div>
<!-- Modal pedido confirmado -->
<div class="modal fade" id="pedidoConfirmadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-cala">

            <div class="modal-body text-center">

                <i class="bi bi-check-circle-fill text-success"
                   style="font-size: 4rem;">
                </i>

                <h5 class="mt-3">
                    ¡Tu pedido fue confirmado!
                </h5>

                <p id="mensajePedidoConfirmado">
                    Tu pedido fue registrado correctamente.
                </p>

            </div>

             <button type="button" class="btn boton-login mt-3" id="btnAceptarPedido">
                Aceptar
            </button>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const opcionesEntrega = document.querySelectorAll('input[name="tipoEntrega"]');

    const datosRetiro = document.getElementById('datosRetiro');

    const datosDelivery = document.getElementById('datosDelivery');

    const sucursalRetiro = document.getElementById('sucursalRetiro');

    const zonaDelivery = document.getElementById('zonaDelivery');

    const direccionRetiro = document.getElementById('direccionRetiro');

    const direccionSucursal = document.getElementById('direccionSucursal');

    const sucursalAsignada = document.getElementById('sucursalAsignada');

    const costoEnvio = document.getElementById('costoEnvio');

    const costoEnvioResumen = document.getElementById('costoEnvioResumen');

    const totalPedido = document.getElementById('totalPedido');

    const mensajeTransferencia = document.getElementById('mensajeTransferencia');

    const titularTransferencia = document.getElementById('titularTransferencia');

    const aliasTransferencia = document.getElementById('aliasTransferencia');

    const cbuTransferencia = document.getElementById('cbuTransferencia');

    const metodosPago = document.querySelectorAll('input[name="metodoPago"]');

    const subtotal = <?= $total ?>;  


    opcionesEntrega.forEach(function (opcion) {

        opcion.addEventListener('change', function () {

            if (this.value === 'delivery') {

                datosDelivery.style.display = 'block';
                datosRetiro.style.display = 'none';

            } else {

                datosDelivery.style.display = 'none';
                datosRetiro.style.display = 'block';

            }
            actualizarDatosTransferencia();

        });

    });

    function actualizarDatosTransferencia() {

        const metodoSeleccionado =
            document.querySelector(
                'input[name="metodoPago"]:checked'
            );

        if (
            !metodoSeleccionado ||
            metodoSeleccionado.value !== 'transferencia'
        ) {

            mensajeTransferencia.style.display = 'none';
            return;
        }

        const tipoEntrega =
            document.querySelector(
                'input[name="tipoEntrega"]:checked'
            ).value;

        let opcionSeleccionada = null;


        if (tipoEntrega === 'retiro') {

            if (!sucursalRetiro.value) {

                mensajeTransferencia.style.display = 'none';
                return;
            }

            opcionSeleccionada =
                sucursalRetiro.options[
                    sucursalRetiro.selectedIndex
                ];
        }

        if (tipoEntrega === 'delivery') {

            if (!zonaDelivery.value) {

                mensajeTransferencia.style.display = 'none';
                return;
            }

            opcionSeleccionada =
                zonaDelivery.options[
                    zonaDelivery.selectedIndex
                ];
        }


        if (!opcionSeleccionada) {

            mensajeTransferencia.style.display = 'none';
            return;
        }

        titularTransferencia.textContent =
            opcionSeleccionada.dataset.titular || '-';

        aliasTransferencia.textContent =
            opcionSeleccionada.dataset.alias || '-';

        cbuTransferencia.textContent =
            opcionSeleccionada.dataset.cbu || '-';

        mensajeTransferencia.style.display = 'block';
    }

    metodosPago.forEach(function (metodo) {

        metodo.addEventListener('change', function () {

            actualizarDatosTransferencia();

        });

    });

    sucursalRetiro.addEventListener('change', function () {

        const opcionSeleccionada =
            this.options[this.selectedIndex];

        const direccion =
            opcionSeleccionada.dataset.direccion;


        if (!this.value) {

            direccionRetiro.style.display = 'none';
            direccionSucursal.textContent = '-';

            actualizarDatosTransferencia();

            return;
        }


        direccionSucursal.textContent = direccion;
        direccionRetiro.style.display = 'block';

        actualizarDatosTransferencia();
    });


    zonaDelivery.addEventListener('change', function () {

        const opcionSeleccionada =
            this.options[this.selectedIndex];

        const nombreSucursal =
            opcionSeleccionada.dataset.nombreSucursal;

        const direccion =
            opcionSeleccionada.dataset.direccion;

        const tarifa =
            opcionSeleccionada.dataset.tarifa;


        if (!this.value) {

            sucursalAsignada.textContent = '-';

            costoEnvio.textContent = '-';

            costoEnvioResumen.textContent = '$0,00';

            totalPedido.textContent =
                '$' + subtotal.toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            actualizarDatosTransferencia();

            return;
        }

        sucursalAsignada.innerHTML =
            `<strong>${nombreSucursal}</strong><br>${direccion}`;
 
        costoEnvio.textContent =
            '$' + parseFloat(tarifa).toLocaleString('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });


        const envio = parseFloat(tarifa);


        costoEnvioResumen.textContent =
            '$' + envio.toLocaleString('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        const total = subtotal + envio;

        totalPedido.textContent =
            '$' + total.toLocaleString('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        actualizarDatosTransferencia();
    });

    const botonConfirmar =
        document.getElementById('btnConfirmarPedido');

    botonConfirmar.addEventListener('click', function () {

        const tipoEntrega =
            document.querySelector(
                'input[name="tipoEntrega"]:checked'
            ).value;

        const metodoPago =
            document.querySelector(
                'input[name="metodoPago"]:checked'
            ).value;

        const direccionInput =
            document.querySelector(
                '#datosDelivery input[type="text"]'
            );


        let idSucursal = null;
        let idZona = null;
        let direccionEntrega = '';

        if (tipoEntrega === 'retiro') {

            idSucursal = sucursalRetiro.value;

            if (!idSucursal) {

                alert('Seleccioná una sucursal.');
                return;
            }
        }

        if (tipoEntrega === 'delivery') {

            idZona = zonaDelivery.value;

            direccionEntrega =
                direccionInput.value.trim();


            if (!idZona) {

                alert('Seleccioná una zona de cobertura.');
                return;
            }


            if (!direccionEntrega) {

                alert('Ingresá tu dirección de entrega.');

                direccionInput.focus();

                return;
            }

            const opcionZona =
                zonaDelivery.options[
                    zonaDelivery.selectedIndex
                ];

            idSucursal =
                opcionZona.dataset.sucursal;
        }

        fetch(
            '<?= base_url('carrito/confirmar-pedido') ?>',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({

                    tipoEntrega: tipoEntrega,

                    idSucursal: idSucursal,

                    idZona: idZona,

                    direccionEntrega: direccionEntrega,

                    metodoPago: metodoPago
                })
            }
        )

        .then(response => response.json())

        .then(data => {

            if (!data.ok) {

                alert(data.mensaje);
                return;
            }

            const mensaje =
                document.getElementById(
                    'mensajePedidoConfirmado'
                );


            if (metodoPago === 'transferencia') {

                mensaje.textContent =
                    'Tu pedido fue registrado correctamente. El pago quedó pendiente de verificación. Te notificaremos cuando sea aprobado y tu pedido pase a preparación.';

            } else {

                mensaje.textContent =
                    'Tu pedido fue registrado correctamente. Te notificaremos cuando pase a preparación.';
            }


            const modal =
                new bootstrap.Modal(
                    document.getElementById(
                        'pedidoConfirmadoModal'
                    )
                );

            modal.show();

            document
                .getElementById('btnAceptarPedido')
                .addEventListener('click', function () {

                    window.location.href =
                        '<?= base_url('/') ?>';

                });

        })

        .catch(error => {

            console.error(error);

            alert(
                'Ocurrió un error al confirmar el pedido.'
            );

        });

    });

});
</script>

<?= $this->endSection() ?>