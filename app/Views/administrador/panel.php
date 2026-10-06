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

            <p>Pedidos de hoy</p>

        </div>

    </div>

    <!-- Productos activos -->
    <div class="col-md-4">

        <div class="card-dashboard">

            <i class="bi bi-cash-stack"></i>

            <h3>$450.000</h3>

            <p>Ventas de hoy</p>

        </div>

    </div>

    <!-- Promociones activas -->
    <div class="col-md-4">

        <div class="card-dashboard">

            <i class="bi bi-people"></i>

            <h3>50</h3>

            <p>Clientes registrados</p>

        </div>

    </div>

</div>
<!-- Gestión de pedidos -->
<div class="gestion-pedidos">

    <div class="titulo-seccion-pedidos">
        <div>
            <h2>Resumen de pedidos</h2>
            <p>Vista preliminar de los pedidos realizados</p>
        </div>

        <span class="proximamente">
            Próximamente
        </span>
    </div>

    <!-- Estados de pedidos -->
    <div class="estados-pedidos">

        <div class="estado-pedido">
            <i class="bi bi-clock"></i>
            <div>
                <span class="cantidad-estado">8</span>
                <span class="nombre-estado">Pendientes</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="bi bi-fire"></i>
            <div>
                <span class="cantidad-estado">4</span>
                <span class="nombre-estado">En preparación</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="fa-solid fa-motorcycle"></i>
            <div>
                <span class="cantidad-estado">3</span>
                <span class="nombre-estado">En camino</span>
            </div>
        </div>

        <div class="estado-pedido">
            <i class="bi bi-check-circle"></i>
            <div>
                <span class="cantidad-estado">17</span>
                <span class="nombre-estado">Entregados</span>
            </div>
        </div>

    </div>

    <!-- Últimos pedidos -->
    <div class="ultimos-pedidos">

        <div class="titulo-ultimos-pedidos">
            <h3>Últimos pedidos</h3>
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

                    <tr>
                        <td>#00125</td>
                        <td>Juan Pérez</td>
                        <td>06/10/2026 10:45</td>
                        <td>Delivery</td>
                        <td>$12.500</td>
                        <td>
                            <span class="estado-pedido-tabla pendiente">
                                Pendiente
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>#00124</td>
                        <td>María López</td>
                        <td>06/10/2026 10:20</td>
                        <td>Retiro en sucursal</td>
                        <td>$18.000</td>
                        <td>
                            <span class="estado-pedido-tabla preparacion">
                                En preparación
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>#00123</td>
                        <td>Pedro Gómez</td>
                        <td>06/10/2026 09:55</td>
                        <td>Delivery</td>
                        <td>$9.500</td>
                        <td>
                            <span class="estado-pedido-tabla camino">
                                En camino
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>#00122</td>
                        <td>Lucía Fernández</td>
                        <td>06/10/2026 09:30</td>
                        <td>Retiro en sucursal</td>
                        <td>$15.200</td>
                        <td>
                            <span class="estado-pedido-tabla entregado">
                                Entregado
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>