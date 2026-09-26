<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo-panel">
            <img src="<?= base_url('img/logoCala.jpeg') ?>" alt="CALA">
        </div>

        <ul class="menu-panel">
            <li class="activo">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </li>

            <li>
                <i class="bi bi-bag-check"></i>
                <span>Pedidos</span>
            </li>

            <li>
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </li>

            <li>
                <i class="bi bi-gift"></i>
                <sapn>Promociones</sapn>
            </li>

            <li>
                <i class="bi bi-shop"></i>
                <span>Sucursales</span>
            </li>

            <li>
                <i class="bi bi-geo-alt"></i>
                <span>Zonas</span>
            </li>

            <li>
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
            </li>
        </ul>

    </aside>

    <!-- Contenido -->
    <main class="contenido-panel">

        <div class="titulo-panel">
            <h1>Bienvenido</h1>
            <p>Panel de administración CALA Delivery Sandwich</p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card-dashboard">
                    <i class="bi bi-bag-check"></i>
                    <h3>15</h3>
                    <p>Pedidos pendientes</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard">
                    <i class="bi bi-box-seam"></i>
                    <h3>32</h3>
                    <p>Productos activos</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard">
                    <i class="bi bi-gift"></i>
                    <h3>8</h3>
                    <p>Promociones activas</p>
                </div>
            </div>

        </div>

        <div class="tabla-pedidos">

            <h3>Pedidos recientes</h3>

            <table class="table">

                <thead>
                    <tr>
                        <th>N° Pedido</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>#25</td>
                        <td>Juan Pérez</td>
                        <td>$15.500</td>
                        <td>
                            <span class="badge bg-warning">
                                Pendiente
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </main>

</div>

<?= $this->endSection() ?>