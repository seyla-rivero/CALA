<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<?php /** @var array $productos */ ?>

<!-- Encabezado -->
<div class="titulo-productos">

    <div>
        <h1>Productos</h1>
        <p>Administrá los productos del menú</p>
    </div>

    <a href="<?= base_url('admin/agregar_producto') ?>" class="btn-agregar-producto">
        <i class="bi bi-plus-lg"></i>
        Agregar producto
    </a>

</div>
<!-- Tabla de productos -->
<div class="tabla-productos">

    <div class="tabla-header">
        <h2>Listado de productos</h2>
        <span><?= count($productos) ?> productos</span>
    </div>

    <div class="table-responsive">

        <table class="table tabla-productos-datos">

            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto): ?>

                    <tr>
                        <td>
                            <img class="imagen-producto" src="<?= base_url('img/' . $producto['urlImagen']) ?>" alt="<?= esc($producto['nombre']) ?>">

                        </td>
                        <td>
                            <strong> <?= esc($producto['nombre']) ?> </strong>

                        </td>
                        <td>
                            <span class="descripcion-producto">
                                <?= esc($producto['descripcion']) ?>
                            </span>

                        </td>
                        <td>

                            <strong class="precio-producto">
                                $<?= number_format($producto['precio'], 2, ',', '.') ?>
                            </strong>
                        </td>
                        <td>

                            <?php if ($producto['activo'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Activo
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    Inactivo
                                </span>

                            <?php endif; ?>

                        </td>
                        <td>
                            <div class="acciones-producto">

                                <button type="button" class="btn-accion editar" title="Modificar producto">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button type="button" class="btn-accion imagen" title="Modificar imagen">
                                    <i class="bi bi-image"></i>
                                </button>

                                <?php if ($producto['activo'] == 1): ?>

                                    <button type="button" class="btn-accion desactivar" title="Desactivar producto">
                                        <i class="bi bi-toggle-on"></i>
                                    </button>

                                <?php else: ?>

                                    <button type="button" class="btn-accion activar" title="Activar producto">
                                        <i class="bi bi-toggle-off"></i>
                                    </button>

                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (session('mensaje')): ?>

<div class="modal fade modal-exito" id="modalExito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Producto agregado</h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>
            </div>

            <div class="modal-body">

                <i class="bi bi-check-circle-fill icono-exito"></i>

                <p class="mensaje-exito">
                    <?= session('mensaje') ?>
                </p>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn-modal-exito"
                        data-bs-dismiss="modal">
                    Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(
            document.getElementById('modalExito')
        );

        modal.show();
    });
</script>

<?php endif; ?>

<?= $this->endSection() ?>