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

            <li class="nav-item">
                <a class="nav-link" href="#">MIS PEDIDOS</a>
            </li>
        </ul>

        <div class="text-white">
            <a class="text-white me-4" href="#">
                <img src="<?= base_url('img/carritoo.jpeg') ?>" class="icono-nav" alt="Carrito">
            </a>

            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                <img src="<?= base_url('img/login.png') ?>" class="icono-nav" alt="Login">
            </a>
        </div>
    </div>
</nav>

