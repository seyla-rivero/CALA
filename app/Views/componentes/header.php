<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALA Delivery Sandwich</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/paginaPrincipal.css') ?>">
</head>
<body>
    <!--Nav-->
    <nav class="navbar navbar-expand-lg nav-logo px-3">
        <a class="navbar-brand text-white fw-bold" href="#">
            <img src="<?= base_url('img/logoCala.png') ?>" alt="Logo CALA" height="50">
            <span class="ms-2 text-white fw-bold">CALA</span>
        </a>

        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto">
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
                <a class="text-white me-3" href="#">
                    <i class="bi bi-cart3"></i>
                </a>

                <a class="text-white" href="#" >
                    <i class="bi bi-person-circle"></i>
                </a>
            </div>
        </div>
    </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
