<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $promociones */ ?>

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
<!-- Tabla de promociones -->
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
                    <th>Promo del día</th>
                    <th>Bebida</th>
                    <th>Empanadas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($promociones as $promocion): ?>
                    <tr>
                        <!-- Imagen -->
                        <td>
                            <img class="imagen-producto" src="<?= base_url('img/' . $promocion['urlImagen']) ?>" alt="<?= esc($promocion['nombre']) ?>">

                        </td>
                        <!-- Nombre -->
                        <td>
                            <strong>
                                <?= esc($promocion['nombre']) ?>
                            </strong>
                        </td>
                        <!-- Descripción -->
                        <td>
                            <span class="descripcion-producto">
                                <?= esc($promocion['descripcion']) ?>
                            </span>
                        </td>
                        <!-- Precio -->
                        <td>
                            <strong class="precio-producto">
                                $<?= number_format($promocion['precio'], 2, ',', '.') ?>
                            </strong>
                        </td>
                        <!-- Promo del día -->
                        <td class="dato-promocion">

                            <span class="titulo-dato-mobile">Promo del día</span>

                            <?php if ($promocion['esPromoDia'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-star-fill"></i>
                                    Sí
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-star"></i>
                                    No
                                </span>

                            <?php endif; ?>

                        </td>
                        <!-- Bebida -->
                        <td class="dato-promocion">

                            <span class="titulo-dato-mobile">Bebida</span>

                            <?php if ($promocion['incluyeBebida'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Sí
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    No
                                </span>

                            <?php endif; ?>

                        </td>
                        <!-- Empanadas -->
                        <td class="dato-promocion">

                            <span class="titulo-dato-mobile">Empanadas</span>

                            <?php if ($promocion['incluyeEmpanadas'] == 1): ?>

                                <span class="estado-producto activo">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Sí
                                </span>

                            <?php else: ?>

                                <span class="estado-producto inactivo">
                                    <i class="bi bi-x-circle-fill"></i>
                                    No
                                </span>

                            <?php endif; ?>

                        </td>
                        <!-- Estado -->
                        <td>

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
                        <!-- Acciones -->
                        <td>

                            <div class="acciones-producto">

                                <!-- Modificar -->
                                <a href="<?= base_url('admin/editar_promocion/' . $promocion['idItem']) ?>" class="btn-accion editar" title="Modificar promoción">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <!-- Modificar imagen -->
                                <a href="<?= base_url('admin/editar_imagen_promocion/' . $promocion['idItem']) ?>" class="btn-accion imagen" title="Modificar imagen">
                                    <i class="bi bi-image"></i>
                                </a>
                                <!-- Activar / desactivar -->
                                <?php if ($promocion['activo'] == 1): ?>

                                    <form
                                        action="<?= site_url('admin/desactivar_promocion/' . $promocion['idItem']) ?>"
                                        method="post"
                                        style="display: inline;"
                                    >

                                        <button
                                            type="submit"
                                            class="btn-accion desactivar"
                                            title="Desactivar promoción"
                                        >
                                            <i class="bi bi-toggle-on"></i>
                                        </button>

                                    </form>

                                <?php else: ?>

                                    <form
                                        action="<?= site_url('admin/activar_promocion/' . $promocion['idItem']) ?>"
                                        method="post"
                                        style="display: inline;"
                                    >

                                        <button
                                            type="submit"
                                            class="btn-accion activar"
                                            title="Activar promoción"
                                        >
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


<?= $this->endSection() ?>