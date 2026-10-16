<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $promociones */ ?>
<?php /** @var array $promocionesActivas */ ?>

<div class="titulo-productos">

    <div>
        <h1>Promociones</h1>
        <p>Administrá las promociones de CALA</p>
    </div>

    <a href="<?= base_url('admin/agregar_promocion') ?>" class="btn-agregar-producto">
        <i class="bi bi-plus-lg"></i>
        Agregar promoción
    </a>

</div>

<div class="tabla-productos">

    <div class="tabla-header">

        <h2>Listado de promociones</h2>

        <span><?= count($promociones) ?> promociones</span>

    </div>

    <div class="table-responsive">

        <table class="table tabla-productos-datos">

            <thead>

                <tr>
                    <th>Imagen</th>
                    <th>Promoción</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Bebida</th>
                    <th>Empanadas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($promociones as $promocion): ?>
                    <tr>
                        <td>
                            <img class="imagen-producto" src="<?= base_url('img/' . $promocion['urlImagen']) ?>" alt="<?= esc($promocion['nombre']) ?>">

                        </td>
                        <td>
                            <strong>
                                <?= esc($promocion['nombre']) ?>
                            </strong>
                        </td>
                        <td>
                            <span class="descripcion-producto">
                                <?= esc($promocion['descripcion']) ?>
                            </span>
                        </td>
                        <td>
                            <strong class="precio-producto">
                                $<?= number_format($promocion['precio'], 2, ',', '.') ?>
                            </strong>
                        </td>
                        <td class="dato-promocion">

                            <span class="titulo-dato-mobile">Bebida</span>

                            <?php if ($promocion['incluyeBebida'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Sí</span>
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    <span>No</span>
                                </span>

                            <?php endif; ?>

                        </td>
                        <td class="dato-promocion">

                            <span class="titulo-dato-mobile">Empanadas</span>

                            <?php if ($promocion['incluyeEmpanadas'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Sí</span>
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    <span>No</span>
                                </span>

                            <?php endif; ?>

                        </td>
                        <td class="dato-promocion">

                            <?php if ($promocion['activo'] == 1): ?>

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
                            
                                <a href="<?= base_url('admin/editar_promocion/' . $promocion['idItem']) ?>" class="btn-accion editar" title="Modificar promoción">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= base_url('admin/editar_imagen_promocion/' . $promocion['idItem']) ?>" class="btn-accion imagen" title="Modificar imagen">
                                    <i class="bi bi-image"></i>
                                </a>
                                <?php if ($promocion['activo'] == 1): ?>

                                    <form action="<?= site_url('admin/desactivar_promocion/' . $promocion['idItem']) ?>" method="post" style="display: inline;">
                                        <button type="submit" class="btn-accion desactivar" title="Desactivar promocion">
                                            <i class="bi bi-toggle-on"></i>
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <form action="<?= site_url('admin/activar_promocion/' . $promocion['idItem']) ?>" method="post" style="display: inline;">
                                        <button type="submit" class="btn-accion activar" title="Activar promocion">
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
<div class="tabla-productos destacados-productos">

    <div class="tabla-header">
        <div>
            <h2>Destacados</h2>
            <p>Administrá las promociones destacadas de CALA</p>
        </div>
    </div>

    <div class="destacados-contenido">

        <div class="destacado-card">

            <div class="destacado-titulo">

                <i class="bi bi-star-fill"></i>

                <div>
                    <h3>Promo del día</h3>
                    <p>Seleccioná las promociones que querés mostrar como promo del día.</p>
                </div>

            </div>

            <form action="<?= base_url('admin/guardar_promo_del_dia') ?>" method="POST">

                <div class="destacado-lista">

                    <?php foreach ($promocionesActivas as $promocion): ?>

                        <label class="destacado-opcion">

                            <input
                                type="checkbox"
                                name="promoDelDia[]"
                                value="<?= $promocion['idItem'] ?>"
                                <?= $promocion['esPromoDia'] == 1 ? 'checked' : '' ?>
                            >

                            <img
                                src="<?= base_url('img/' . $promocion['urlImagen']) ?>"
                                alt="<?= esc($promocion['nombre']) ?>"
                            >

                            <span>
                                <?= esc($promocion['nombre']) ?>
                            </span>

                        </label>

                    <?php endforeach; ?>

                </div>

                <button type="submit" class="btn-guardar-destacado">

                    <i class="bi bi-check-lg"></i>

                    Guardar promo del día

                </button>

            </form>

        </div>
        <div class="destacado-card">

            <div class="destacado-titulo">

                <i class="bi bi-trophy-fill"></i>

                <div>
                    <h3>La más vendida</h3>
                    <p>Seleccioná la promoción que querés mostrar como la más vendida.</p>
                </div>

            </div>

            <form action="<?= base_url('admin/guardar_mas_vendida') ?>" method="POST">

                <div class="destacado-lista">

                    <?php foreach ($promocionesActivas as $promocion): ?>

                        <label class="destacado-opcion">

                            <input
                                type="radio"
                                name="masVendida"
                                value="<?= $promocion['idItem'] ?>"
                                <?= $promocion['esMasVendida'] == 1 ? 'checked' : '' ?>
                            >

                            <img
                                src="<?= base_url('img/' . $promocion['urlImagen']) ?>"
                                alt="<?= esc($promocion['nombre']) ?>"
                            >

                            <span>
                                <?= esc($promocion['nombre']) ?>
                            </span>

                        </label>

                    <?php endforeach; ?>
                    
                    <label class="destacado-opcion">
                        <input
                            type="radio"
                            name="masVendida"
                            value=""
                            <?= empty(array_filter($promocionesActivas, fn($p) => $p['esMasVendida'] == 1)) ? 'checked' : '' ?>
                        >

                        <span>
                            Ninguna
                        </span>
                    </label>

                </div>

                <button type="submit" class="btn-guardar-destacado">
                    <i class="bi bi-check-lg"></i>
                    Guardar la más vendida
                </button>

            </form>

        </div>
    </div>

</div>
<?php if (session()->getFlashdata('mensajeExito')): ?>

    <div class="modal fade modal-exito" id="modalExito" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Operación realizada</h5>

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