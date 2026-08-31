<!--Nav-->
<nav class="navbar navbar-expand-lg nav-logo px-3">
    <a class="navbar-brand text-white fw-bold" href="#">
        <img src="<?= base_url('img/logoCala.jpeg') ?>" alt="Logo CALA" height="60">
    </a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mx-auto gap-5">
            <li class="nav-item">
                <a class="nav-link active" href="#">INICIO</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">MENÚ</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">PROMOCIONES</a>
            </li>

            <?php if(session('logueado')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">MIS PEDIDOS</a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="text-white">
            
            <?php if(session('logueado')): ?>

                <div class="d-flex align-items-center gap-3">

                    <a class="text-white me-4" href="#">
                        <img src="<?= base_url('img/carritoo.jpeg') ?>" class="icono-nav" alt="Carrito">
                    </a>

                    <span class="text-white fw-bold">
                        Hola, <?= session('nombre') ?>
                    </span>

                    <a href="<?= site_url('logout') ?>" class="text-white">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>

                </div>

            <?php else: ?>

                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <img src="<?= base_url('img/login.png') ?>" class="icono-nav" alt="Login">
                </a>

            <?php endif; ?>
        </div>
    </div>
</nav>

