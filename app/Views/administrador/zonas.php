<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<?php /** @var array $zonas */ ?>

<div class="titulo-productos">

    <div>
        <h1>Zonas</h1>
        <p>Administrá las zonas de entrega de tu sucursal</p>
    </div>

    <a href="<?= base_url('admin/agregar_zona') ?>" class="btn-agregar-producto">
        <i class="bi bi-plus-lg"></i>
        Agregar zona
    </a>

</div>

<div class="tabla-productos">

    <div class="tabla-header">

        <h2>Listado de zonas</h2>

        <span><?= count($zonas) ?> zonas</span>

    </div>

    <div class="table-responsive">

        <table class="table tabla-productos-datos">

            <thead>

                <tr>
                    <th>Zona</th>
                    <th>Costo de envío</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($zonas as $zona): ?>

                    <tr>

                        <td>
                            <strong>
                                <?= esc($zona['nombre']) ?>
                            </strong>
                        </td>

                        <td>
                            <strong class="precio-producto">
                                $<?= number_format($zona['costoEnvio'], 2, ',', '.') ?>
                            </strong>
                        </td>

                        <td>

                            <?php if ($zona['activo'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Activa
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    Inactiva
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="acciones-producto">

                                <a href="<?= base_url('admin/editar_zona/' . $zona['idZona']) ?>"
                                   class="btn-accion editar"
                                   title="Modificar zona">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <?php if ($zona['activo'] == 1): ?>

                                    <form action="<?= site_url('admin/desactivar_zona/' . $zona['idZona']) ?>"
                                          method="post"
                                          style="display: inline;">

                                        <button type="submit"
                                                class="btn-accion desactivar"
                                                title="Desactivar zona">

                                            <i class="bi bi-toggle-on"></i>

                                        </button>

                                    </form>

                                <?php else: ?>

                                    <form action="<?= site_url('admin/activar_zona/' . $zona['idZona']) ?>"
                                          method="post"
                                          style="display: inline;">

                                        <button type="submit"
                                                class="btn-accion activar"
                                                title="Activar zona">

                                            <i class="bi bi-toggle-off"></i>

                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>
<?php if (session()->getFlashdata('mensajeExito')): ?>

    <div class="modal fade modal-exito" id="modalExito" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">¡Guardado correctamente!</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    </button>
                </div>

                <div class="modal-body">

                    <i class="bi bi-check-circle-fill icono-exito"></i>

                    <p class="mensaje-exito">
                        <?= esc(session()->getFlashdata('mensajeExito')) ?>
                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn-modal-exito" data-bs-dismiss="modal">
                        Aceptar
                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modalExito = new bootstrap.Modal(
                document.getElementById('modalExito')
            );

            modalExito.show();

        });
    </script>

<?php endif; ?>

<?= $this->endSection() ?>