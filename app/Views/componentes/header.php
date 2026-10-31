
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg nav-logo px-3">

    <!-- LOGO -->
    <a href="<?= base_url('/') ?>" class="logo-link">
        <img src="<?= base_url('img/logoCala.jpeg') ?>"
             alt="CALA Delivery"
             class="logo-cala">
    </a>

    <!-- CONTENEDOR DE ACCIONES -->
    <div class="navbar-acciones" id="accionesNavbar">

        <?php if (session('logueado')): ?>

            <?php
                $notificaciones = $notificaciones ?? [];
                $cantidadNotificaciones = $cantidadNotificacionesNoLeidas ?? 0;
            ?>

            <!-- CAMPANITA -->
            <div class="dropdown">

                <button
                    class="btn campana-notificaciones position-relative"
                    type="button"
                    id="btnNotificaciones"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Ver notificaciones"
                >
                    <i class="bi bi-bell-fill fs-4"></i>

                    <?php if ($cantidadNotificaciones > 0): ?>
                        <span class="badge-notificaciones">
                            <?= $cantidadNotificaciones > 99
                                ? '99+'
                                : $cantidadNotificaciones ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- PANEL DE NOTIFICACIONES -->
                <div class="dropdown-menu dropdown-menu-end panel-notificaciones p-0"
                     aria-labelledby="btnNotificaciones">

                    <div class="encabezado-notificaciones">
                        <strong>Notificaciones</strong>

                        <span>
                            <?= $cantidadNotificaciones ?>
                            <?= $cantidadNotificaciones === 1
                                ? 'mensaje'
                                : 'mensajes' ?>
                        </span>
                    </div>

                    <div class="lista-notificaciones">

                        <?php if (empty($notificaciones)): ?>

                            <div class="notificaciones-vacias">
                                <i class="bi bi-bell-slash fs-2"></i>
                                <p class="mb-0 mt-2">
                                    No tenés notificaciones por ahora.
                                </p>
                            </div>

                        <?php else: ?>

                            <?php foreach ($notificaciones as $notificacion): ?>

                                <div class="item-notificacion">

                                    <div class="icono-notificacion">
                                        <i class="bi bi-info-circle-fill"></i>
                                    </div>

                                    <div class="contenido-notificacion">

                                        <p class="mb-1">
                                            <?= esc($notificacion['mensaje']) ?>
                                        </p>

                                        <small>
                                            <?= !empty($notificacion['fecha'])
                                                ? date(
                                                    'd/m/Y H:i',
                                                    strtotime($notificacion['fecha'])
                                                )
                                                : '' ?>
                                        </small>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>

                    <a href="<?= base_url('mis-pedidos') ?>"
                       class="pie-notificaciones">
                        Ver mis pedidos
                    </a>

                    <form
                        id="formMarcarNotificacionesLeidas"
                        action="<?= base_url('notificaciones/marcar-leidas') ?>"
                        method="post"
                        class="d-none"
                    >
                        <?= csrf_field() ?>
                    </form>

                </div>
            </div>

            <!-- CARRITO -->
            <div class="carrito-icono">

                <a class="text-white"
                   href="<?= base_url('carrito') ?>">

                    <img
                        src="<?= base_url('img/carritoo.jpeg') ?>"
                        class="icono-nav"
                        alt="Carrito"
                    >

                    <span id="contadorCarrito"></span>

                </a>

            </div>

        <?php endif; ?>

        <!-- HAMBURGUESA -->
        <button
            class="navbar-toggler btn-menu"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
            aria-controls="menu"
            aria-expanded="false"
            aria-label="Abrir menú"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>

    <!-- MENÚ DESPLEGABLE -->
    <div class="collapse navbar-collapse" id="menu">

        <!-- ENLACES -->
        <ul class="navbar-nav mx-auto gap-2">

            <li class="nav-item">
                <a
                    class="nav-link <?= uri_string() == '' ? 'active' : '' ?>"
                    href="<?= base_url('/') ?>"
                >
                    INICIO
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link <?= uri_string() == 'menu' ? 'active' : '' ?>"
                    href="<?= base_url('menu') ?>"
                >
                    MENÚ
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link <?= uri_string() == 'promociones' ? 'active' : '' ?>"
                    href="<?= base_url('promociones') ?>"
                >
                    PROMOCIONES
                </a>
            </li>

            <?php if (session('logueado')): ?>

                <li class="nav-item">
                    <a
                        class="nav-link <?= (
                            uri_string() === 'mis-pedidos' ||
                            strpos(uri_string(), 'mis-pedidos/detalle/') === 0
                        ) ? 'active' : '' ?>"
                        href="<?= base_url('mis-pedidos') ?>"
                    >
                        MIS PEDIDOS
                    </a>
                </li>

            <?php endif; ?>

        </ul>

        <!-- USUARIO -->
        <div class="usuario-navbar text-white" id="usuarioNavbar">

            <?php if (session('logueado')): ?>

                <div class="d-flex align-items-center gap-3">

                    <span class="text-white fw-bold">
                        Hola, <?= esc(session('nombre')) ?>
                    </span>

                    <a
                        href="<?= site_url('logout') ?>"
                        class="text-white ms-2"
                        title="Cerrar sesión"
                    >
                        <i class="bi bi-box-arrow-right fs-4"></i>
                    </a>

                </div>

            <?php else: ?>

                <div class="text-center mt-3">

                    <a
                        href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#loginModal"
                        class="text-white text-decoration-none fw-bold"
                    >
                        <i class="bi bi-person-fill fs-3"></i>
                        <br>
                        Iniciar sesión
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</nav>

<!-- MARCAR NOTIFICACIONES COMO LEÍDAS -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const campana = document.getElementById('btnNotificaciones');

    const formulario = document.getElementById(
        'formMarcarNotificacionesLeidas'
    );

    if (!campana || !formulario) return;

    campana.addEventListener('shown.bs.dropdown', async function () {

        const badge = campana.querySelector('.badge-notificaciones');

        if (!badge) return;

        try {

            const respuesta = await fetch(formulario.action, {
                method: 'POST',
                body: new FormData(formulario),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!respuesta.ok) {
                console.error('No se pudieron marcar las notificaciones.');
                return;
            }

            const resultado = await respuesta.json();

            if (resultado.ok) {
                badge.remove();
            }

        } catch (error) {

            console.error(
                'Error al actualizar las notificaciones:',
                error
            );

        }

    });

});
</script>

<!-- REUBICAR ACCIONES SEGÚN PANTALLA -->
<script>

document.addEventListener('DOMContentLoaded', function () {

    const acciones = document.getElementById('accionesNavbar');
    const usuario = document.getElementById('usuarioNavbar');
    const menu = document.getElementById('menu');
    const navbar = document.querySelector('.nav-logo');

    if (!acciones || !usuario || !menu || !navbar) return;

    const logo = navbar.querySelector('.logo-link');
    const hamburguesa = acciones.querySelector('.navbar-toggler');

    // Guardamos el lugar original del usuario
    const usuarioOriginal = usuario.parentElement;

    function acomodarNavbar() {

        if (window.innerWidth >= 992) {

            // Computadora: usuario al final de las acciones
            menu.appendChild(acciones);
            acciones.appendChild(usuario);

        } else {

            // Celular: acciones junto al logo
            if (logo) {
                logo.after(acciones);
            }

            // Usuario vuelve al menú desplegable
            menu.appendChild(usuario);

        }

        // La hamburguesa siempre queda al final de las acciones
        if (hamburguesa) {
            acciones.appendChild(hamburguesa);
        }
    }

    acomodarNavbar();

    window.addEventListener('resize', acomodarNavbar);

});

</script>

