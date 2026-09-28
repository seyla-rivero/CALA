<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<div class="titulo-panel">

    <h1>Bienvenido</h1>

    <p>Panel de administración CALA Delivery Sandwich</p>

</div>

<!-- Cards de información -->
<div class="row g-4">

    <!-- Pedidos pendientes -->
    <div class="col-md-4">

        <div class="card-dashboard">

            <i class="bi bi-bag-check"></i>

            <h3>15</h3>

            <p>Pedidos pendientes</p>

        </div>

    </div>

    <!-- Productos activos -->
    <div class="col-md-4">

        <div class="card-dashboard">

            <i class="bi bi-box-seam"></i>

            <h3>32</h3>

            <p>Productos activos</p>

        </div>

    </div>

    <!-- Promociones activas -->
    <div class="col-md-4">

        <div class="card-dashboard">

            <i class="bi bi-gift"></i>

            <h3>8</h3>

            <p>Promociones activas</p>

        </div>

    </div>

</div>

<?= $this->endSection() ?>