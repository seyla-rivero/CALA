<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid bg-white p-0">
    <!-- Portada -->
    <div class="p-4">
        <div class="portada">
            Imagen Portada
        </div>
    </div>

    <!-- Cards -->
    <div class="container-fluid px-4">
        <div class="row g-3">

            <div class="col-md-4">
                <div class="card-promo">
                    <h2 class="fw-bold">PROMO DEL DÍA</h2>

                    <p class="fw-bold">
                        Hamburguesa super + papas
                    </p>

                    <div class="precio">
                        $15.000
                    </div>

                    <button class="btn btn-cala">
                        VER PROMO
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-promo">
                    <h2 class="fw-bold">LA MÁS VENDIDA</h2>

                    <p class="fw-bold">
                        Burgerpizza + papas
                    </p>

                    <div class="precio">
                        $33.000
                    </div>

                    <button class="btn btn-cala">
                        VER PROMO
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-promo">
                    <h2 class="fw-bold">
                        RETIRO O DELIVERY
                    </h2>

                    <p class="fw-bold">
                        Elegí cómo recibir tu pedido.
                        Retiro en sucursal o envío a domicilio.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Sucursales -->
    <section class="sucursales p-4">

        <h3 class="text-center fw-bold mb-4">
            NUESTRAS SUCURSALES
        </h3>

        <div class="row">
            <div class="col-md-6 border-end">

                <h4 class="titulo-sucursal">
                    SUCURSAL 1
                </h4>

                <div class="sucursal-contenido">
                    <div class="sucursal-datos">
                        <p>
                            <img src="<?= base_url('img/ubicacion.png') ?>" alt="Ubicación" height="30">
                            Montes de Oca 394, Godoy Cruz
                        </p>
                        <p>
                            <img src="<?= base_url('img/horario.png') ?>" alt="Horario" height="30">
                            Miercoles a Domingos 20:00pm - 23:59pm
                        </p>
                        <p>
                            <img src="<?= base_url('img/what.png') ?>" alt="WhatsApp" height="25">
                            2615726223
                        </p>
                    </div>

                    <div class="mapa">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1469.4242509176713!2d-68.86805619576329!3d-32.91951596119259!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967e0984c3423023%3A0x686009755c123bec!2sMontes%20de%20Oca%20394%2C%20M5504%20Godoy%20Cruz%2C%20Mendoza%2C%20Argentina!5e0!3m2!1sen!2sus!4v1788018191682!5m2!1sen!2sus" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                        </iframe>
                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <h4 class="titulo-sucursal">
                    SUCURSAL 2
                </h4>
                <div class="sucursal-contenido">
                    <div class="sucursal-datos">
                        <p>
                            <img src="<?= base_url('img/ubicacion.png') ?>" alt="Ubicación" height="30">
                            Pres.R.Ortiz 1665, Godoy Cruz
                        </p>
                        <p>
                            <img src="<?= base_url('img/horario.png') ?>" alt="Horario" height="30">
                            Miercoles a Domingos 21:00pm - 23:59pm<br>
                            Viernes a Domingos 12:30pm - 14:00pm
                        </p>
                        <p>
                            <img src="<?= base_url('img/what.png') ?>" alt="WhatsApp" height="25">
                            2615687706
                        </p>
                    </div>

                    <div class="mapa">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1674.7650510413835!2d-68.862573!3d-32.91059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967e099eec405ccb%3A0x8af2665564d85eb0!2sPres.%20Roberto%20M.%20Ortiz%201665%2C%20M5501%20Godoy%20Cruz%2C%20Mendoza%2C%20Argentina!5e0!3m2!1sen!2sus!4v1788018016343!5m2!1sen!2sus" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>

    </section>

</div>
<?php if(session()->getFlashdata('success')): ?>

<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-cala">

            <div class="modal-body text-center p-4">

                <i class="bi bi-check-circle-fill text-success"
                   style="font-size: 4rem;">
                </i>

                <h3 class="mt-3">¡Registro exitoso!</h3>

                <p>
                    <?= session()->getFlashdata('success') ?>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    let modal = new bootstrap.Modal(
        document.getElementById('successModal')
    );
    modal.show();
});
</script>

<?php endif; ?>
<?= $this->endSection() ?>