<aside class="sidebar">

    <div class="logo-panel">
        <img src="<?= base_url('img/logoCala.jpeg') ?>" alt="CALA">
    </div>

    <!-- Administrador -->
    <div class="usuario-panel">
        <i class="bi bi-person-circle"></i>
        <div>
            <span class="nombre-admin">
                <?= session()->get('nombreAdministrador') ?>
            </span>
            <span class="rol-admin">Administrador</span>
        </div>
    </div>

    <ul class="menu-panel">

        <!-- Dashboard -->
        <li class="<?= uri_string() == 'admin/panel' ? 'activo' : '' ?>">
            <a href="<?= base_url('admin/panel') ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Panel principal</span>
            </a>
        </li>

        <!-- Pedidos -->
        <li class="<?= uri_string() == 'admin/pedidos' ? 'activo' : '' ?>">
            <a href="<?= base_url('admin/pedidos') ?>">
                <i class="bi bi-receipt"></i>
                <span>Pedidos</span>
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
           <a href="<?= site_url('logoutAdmi') ?>" title="Cerrar sesión">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
            </a>
        </li>

    </ul>

</aside>