<!--Nav-->
<nav class="navbar navbar-expand-lg nav-logo px-3">
    <a href="<?= base_url('/') ?>" class="logo-link">
        <img src="<?= base_url('img/logoCala.jpeg') ?>" alt="CALA Delivery" class="logo-cala">
    </a>

    <button class="navbar-toggler btn-menu" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mx-auto gap-2">
            <li class="nav-item">
                <a class="nav-link <?= uri_string() == '' ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                INICIO
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= uri_string() == 'menu' ? 'active' : '' ?>" href="<?= base_url('menu') ?>">
                MENÚ
            </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= uri_string() == 'promociones' ? 'active' : '' ?>" href="<?= base_url('promociones') ?>">
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

        <div class="text-white">
            
            <?php if(session('logueado')): ?>
                
            <div class="d-flex align-items-center gap-3">

                <?php
                    $notificaciones = $notificaciones ?? [];
                    $cantidadNotificaciones = $cantidadNotificacionesNoLeidas ?? 0;
                ?>

                <!-- Campanita de notificaciones -->
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

                    <div class="dropdown-menu dropdown-menu-end panel-notificaciones p-0" aria-labelledby="btnNotificaciones">
                        <div class="encabezado-notificaciones">
                            <strong>Notificaciones</strong>
                            <span><?= $cantidadNotificaciones ?>
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

                        <a href="<?= base_url('mis-pedidos') ?>" class="pie-notificaciones">
                            Ver mis pedidos
                        </a>
                        <!-- Formulario oculto para marcar como leídas -->
                        <form id="formMarcarNotificacionesLeidas" action="<?= base_url('notificaciones/marcar-leidas') ?>" method="post" class="d-none">
                            <?= csrf_field() ?>
                        </form>
                    </div>
                </div>

                <!-- Carrito -->
                <div class="carrito-icono">
                    <a class="text-white me-4" href="<?= base_url('carrito') ?>">
                        <img
                            src="<?= base_url('img/carritoo.jpeg') ?>"
                            class="icono-nav"
                            alt="Carrito"
                        >
                        <span id="contadorCarrito"></span>
                    </a>
                </div>

                <!-- Saludo -->
                <span class="text-white fw-bold">
                    Hola, <?= esc(session('nombre')) ?>
                </span>

                <!-- Cerrar sesión -->
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
                    <a href="#"
                    data-bs-toggle="modal"
                    data-bs-target="#loginModal"
                    class="text-white text-decoration-none fw-bold">

                        <i class="bi bi-person-fill fs-3"></i><br>
                        Iniciar sesión

                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
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
            console.error('Error al actualizar las notificaciones:', error);
        }
    });
});
</script>
