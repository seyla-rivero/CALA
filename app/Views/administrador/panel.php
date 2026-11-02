<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var float|int $totalPedidosHoy */ ?>
<?php /** @var float|int $ventasHoy */ ?>
<?php /** @var float|int $totalClientes */ ?>
<?php /** @var array $estadosPedidos */ ?>

<div class="titulo-panel">
    <h1>Bienvenido</h1>
    <p>Panel de administración CALA Delivery Sandwich</p>
</div>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card-dashboard">
            <i class="bi bi-bag-check"></i>
            <h3><?= number_format($totalPedidosHoy, 0, ',', '.') ?></h3>
            <p>Pedidos de hoy</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-dashboard">
            <i class="bi bi-cash-stack"></i>
            <h3>$<?= number_format($ventasHoy, 2, ',', '.') ?></h3>
            <p>Ventas de hoy</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-dashboard">
            <i class="bi bi-people"></i>
            <h3><?= number_format($totalClientes, 0, ',', '.') ?></h3>
            <p>Clientes registrados</p>
        </div>
    </div>

</div>

<div class="gestion-pedidos">

    <div class="titulo-seccion-pedidos">
        <div>
            <h2>Resumen de pedidos de hoy</h2>
            <p>Estado actual de los pedidos de tu sucursal</p>
        </div>

        <a href="<?= base_url('admin/pedidos') ?>"
           class="btn-ver-pedidos">
            Ver todos los pedidos
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="estados-pedidos">

        <div class="estado-pedido">
            <i class="bi bi-clock"></i>
            <div>
                <span class="cantidad-estado">
                    <?= $estadosPedidos['pendientes'] ?>
                </span>
                <span class="nombre-estado">Pendientes</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="bi bi-fire"></i>
            <div>
                <span class="cantidad-estado">
                    <?= $estadosPedidos['preparacion'] ?>
                </span>
                <span class="nombre-estado">En preparación</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="bi bi-check-circle"></i>
            <div>
                <span class="cantidad-estado">
                    <?= $estadosPedidos['listos'] ?>
                </span>
                <span class="nombre-estado">Listos</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="bi bi-bag-check"></i>
            <div>
                <span class="cantidad-estado">
                    <?= $estadosPedidos['finalizados'] ?>
                </span>
                <span class="nombre-estado">Finalizados</span>
            </div>
        </div>

    </div>

    <div class="ultimos-pedidos">

        <div class="titulo-ultimos-pedidos">
            <h3>Últimos pedidos</h3>
            <p>Los cinco pedidos más recientes de tu sucursal</p>
        </div>

        <div class="table-responsive">

            <table class="tabla-pedidos-dashboard">

                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha y hora</th>
                        <th>Tipo de entrega</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($ultimosPedidos)): ?>

                        <?php foreach ($ultimosPedidos as $pedido): ?>

                            <tr>
                                <td>
                                    #<?= esc($pedido['numeroDiario']) ?>
                                </td>

                                <td>
                                    <?= esc($pedido['nombreCompleto']) ?>
                                </td>

                                <td>
                                    <?= esc($pedido['fechaMostrar']) ?>
                                </td>

                                <td>
                                    <?= esc($pedido['textoEntrega']) ?>
                                </td>

                                <td>
                                    $<?= number_format(
                                        (float) ($pedido['total'] ?? 0),
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <span class="estado-pedido-tabla <?= esc($pedido['claseEstado']) ?>">
                                        <?= esc($pedido['textoEstado']) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="sin-pedidos">
                                <i class="bi bi-inbox"></i>
                                <p>No hay pedidos registrados todavía.</p>
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
