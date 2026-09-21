<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $promociones */ ?>
<?php /** @var array $masVendida */ ?>
<?php /** @var array $promoDelDia */ ?>

<div class="container-fluid bg-white p-0">
    <!-- Portada -->
    <div class="p-4">
        <div class="portada">
            <img src="<?= base_url('img/CALAPortada.jpeg') ?>" alt="CALA Delivery Sandwich">
        </div>
    </div>

    <!-- Cards -->
    <div class="container-fluid px-4">
        <div class="row g-3">

            <!-- Promos del día -->
            <?php if (!empty($promoDelDia)): ?>
                <div class="col-md-4">
                    <div class="banner-promo">

                        <!-- Imagen decorativa -->
                        <img
                            id="promoImagen"
                            src="<?= base_url('img/Promodeldia.png') ?>"
                            alt="Comidas de CALA"
                            class="promo-comida">

                        <!-- Información de la promo -->
                        <div class="banner-overlay">

                            <span class="promo-titulo">
                                PROMO <span>DEL DÍA</span>
                            </span>

                            <h3 id="promoNombre"></h3>

                            <span class="promo-precio" id="promoPrecio"></span>

                            <button
                                type="button"
                                class="btn-promo"
                                onclick="verPromo()">
                                Ver promo
                            </button>

                        </div>

                        <!-- Flechas -->
                        <button
                            type="button"
                            class="flecha flecha-anterior"
                            id="flechaAnterior"
                            onclick="promoAnterior()">
                            &#10094;
                        </button>

                        <button
                            type="button"
                            class="flecha flecha-siguiente"
                            id="flechaSiguiente"
                            onclick="promoSiguiente()">
                            &#10095;
                        </button>

                    </div>

                    <!-- Indicadores -->
                    <div class="indicadores" id="indicadoresPromos"></div>
                </div>
            <?php endif; ?>    

            <!-- La más vendida -->
            <div class="<?= $promoDelDia ? 'col-md-4' : 'col-md-6' ?>">

                <div class="banner-promo mas-vendida">

                    <img
                        src="<?= base_url('img/lamasvendidaa.png') ?>"
                        alt="Hamburguesa más vendida"
                        class="img-mas-vendida">

                    <div class="banner-overlay">

                        <span class="vendida-titulo">
                            LA MÁS <span>VENDIDA</span>
                        </span>

                        <h3><?= esc($masVendida['nombre']) ?></h3>

                        <span class="promo-precio">
                            $<?= number_format($masVendida['precio'], 0, ',', '.') ?>
                        </span>

                        <button
                            type="button"
                            class="btn-promo"
                            onclick="verMasVendida()">
                            Ver promo
                        </button>

                    </div>

                </div>

            </div>

            <!-- Retiro o delivery -->
           <div class="<?= $promoDelDia ? 'col-md-4' : 'col-md-6' ?>">

                <div class="banner-promo retiro-delivery">

                    <img src="<?= base_url('img/bolsaDelivery.png') ?>"
                        alt="CALA Delivery"
                        class="img-bolsa-cala">

                    <div class="retiro-overlay">

                        <h3>
                            RETIRO O <span>DELIVERY</span>
                        </h3>

                        <p>Tu pedido, como prefieras</p>

                        <div class="opciones-entrega">

                            <div class="opcion">
                                <i class="bi bi-shop"></i>
                                <span>Retirá en<br>sucursal</span>
                            </div>

                            <div class="separador"></div>

                            <div class="opcion">
                                <i class="bi bi-truck-front"></i>
                                <span>Recibilo en<br>tu domicilio</span>
                            </div>

                        </div>

                    </div>

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
                            <i class="bi bi-geo-alt icono-sucursal"></i>
                            <span>Montes de Oca 394, Godoy Cruz</span>
                        </p>
                        <p>
                            <i class="bi bi-clock icono-sucursal"></i>
                            <span>Miercoles a Domingos 20:00pm - 23:59pm</span>
                        </p>
                        <p>
                            <a href="https://wa.me/542615726223"
                            target="_blank"
                            class="icono-whatsapp">
                                <i class="bi bi-whatsapp"></i>
                            </a>

                            <span>2615726223</span>
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
                            <i class="bi bi-geo-alt icono-sucursal"></i>
                            <span>Pres.R.Ortiz 1665, Godoy Cruz</span>
                        </p>
                        <p>
                            <i class="bi bi-clock icono-sucursal"></i>
                            <span>Miercoles a Domingos 21:00pm - 23:59pm<br>
                            Viernes a Domingos 12:30pm - 14:00pm</span>
                        </p>
                        <p>
                            <a href="https://wa.me/542615687706"
                            target="_blank"
                            class="icono-whatsapp">
                                <i class="bi bi-whatsapp"></i>
                            </a>

                            <span>2615687706</span>
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
<?php endif; ?>
<!--Modal del detalle de promo-->
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

             <!-- Selección de bebida -->
            <div id="contenedorBebida" class="bebida-producto" style="display: none;">

                <select id="bebida" class="form-select">
                    <option value="">Elegí tu bebida</option>
                    <option value="Talca cola 3 litros">Talca cola 3 litros</option>
                    <option value="Talca lima 3 litros">Talca lima 3 litros</option>
                    <option value="Talca naranja 3 litros">Talca naranja 3 litros</option>
                    <option value="Talca pomelo 3 litros">Talca pomelo 3 litros</option>
                </select>
            </div>
            <!-- Selección empanadas -->
            <div id="contenedorEmpanadas" class="empanadas-producto" style="display: none;">

                <select id="empanadas" class="form-select">
                    <option value="">Elegí empanadas</option>
                    <option value="Criollas">Criollas</option>
                    <option value="Jamon y queso">Jamón y queso</option>
                </select>

            </div>

            <div class="cantidad">

                <span class="cantidad-label mt-4">Cantidad:</span>

                <button type="button" class="btn btn-outline-secondary mt-4" onclick="disminuirCantidad()">−</button>

                <span id="cantidad" class="cantidad-numero mt-4">1</span>

                <button type="button" class="btn btn-outline-secondary mt-4" onclick="aumentarCantidad()">+</button>

            </div>

            <div class="comentario-producto">
                <label for="comentario">Comentario:</label>

                <textarea id="comentario" name="comentario" placeholder="Ej.: Sin mayonesa, sin cebolla..." maxlength="200"></textarea>
            </div>

            <button type="button" id="btnAgregarPedido" class="btn-agregar mt-3" onclick="agregarAlCarrito()">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    let modal = new bootstrap.Modal(
        document.getElementById('successModal')
    );
    modal.show();
});
</script>
<script>
const promocionesDia = <?= json_encode($promoDelDia) ?>;

let promoActual = 0;

function mostrarPromo() {

    if (promocionesDia.length === 0) return;

    const promo = promocionesDia[promoActual];

    document.getElementById("promoNombre").textContent =
        promo.nombre;

    document.getElementById("promoPrecio").textContent =
        "$" + Number(promo.precio).toLocaleString('es-AR');

    const indicadores =
        document.querySelectorAll(".indicador");

    indicadores.forEach((indicador, index) => {

        indicador.classList.toggle(
            "activo",
            index === promoActual
        );

    });

    // Mostrar flechas e indicadores solo si hay más de una promo
    const mostrarControles = promocionesDia.length > 1;

    document.getElementById("flechaAnterior").style.display =
        mostrarControles ? "block" : "none";

    document.getElementById("flechaSiguiente").style.display =
        mostrarControles ? "block" : "none";

    document.getElementById("indicadoresPromos").style.display =
        mostrarControles ? "flex" : "none";
}


function promoSiguiente() {

    promoActual++;

    if (promoActual >= promocionesDia.length) {
        promoActual = 0;
    }

    mostrarPromo();
}


function promoAnterior() {

    promoActual--;

    if (promoActual < 0) {
        promoActual = promocionesDia.length - 1;
    }

    mostrarPromo();
}


document.addEventListener("DOMContentLoaded", function() {
    mostrarPromo();
});


function verPromo() {

    if (promocionesDia.length === 0) return;

    const promo = promocionesDia[promoActual];

    abrirModal(
        promo.idItem,
        promo.nombre,
        promo.descripcion,
        promo.precio,
        "<?= base_url('img/') ?>" + promo.urlImagen,
        promo.incluyeBebida,
        promo.incluyeEmpanadas
    );
}


const masVendida = <?= json_encode($masVendida) ?>;

function verMasVendida() {

    abrirModal(
        masVendida.idItem,
        masVendida.nombre,
        masVendida.descripcion,
        masVendida.precio,
        "<?= base_url('img/') ?>" + masVendida.urlImagen
    );
}
</script>

<?= $this->endSection() ?>