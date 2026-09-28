<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - CALA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/administrador.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/panel.css') ?>">
</head>
<body>

    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-panel">
                <img src="<?= base_url('img/logoCala.jpeg') ?>" alt="CALA">
            </div>

            <ul class="menu-panel">

                <!-- Dashboard -->
                <li class="<?= uri_string() == 'admin/panel' ? 'activo' : '' ?>">
                    <a href="<?= base_url('admin/panel') ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Productos -->
                <li class="<?= uri_string() == 'admin/productos' ? 'activo' : '' ?>">
                    <a href="<?= base_url('admin/productos') ?>">
                        <i class="bi bi-box-seam"></i>
                        <span>Productos</span>
                    </a>
                </li>

                <!-- Promociones -->
                <li class="<?= uri_string() == 'admin/promociones' ? 'activo' : '' ?>">
                    <a href="<?= base_url('admin/promociones') ?>">
                        <i class="bi bi-gift"></i>
                        <span>Promociones</span>
                    </a>
                </li>

                <!-- Zonas -->
                <li class="<?= uri_string() == 'admin/zonas' ? 'activo' : '' ?>">
                    <a href="<?= base_url('admin/zonas') ?>">
                        <i class="bi bi-geo-alt"></i>
                        <span>Zonas</span>
                    </a>
                </li>

                <!-- Cerrar sesión -->
                <li>
                    <a href="#">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </li>

            </ul>

        </aside>

        <main class="contenido-panel">
            <?= $this->renderSection('contenido') ?>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>