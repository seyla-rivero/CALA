<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $zona */ ?>

<div class="titulo-productos">

    <div>
        <h1>Editar zona</h1>
        <p>Modificá los datos de la zona</p>
    </div>

</div>

<div class="formulario-producto">

    <form action="<?= site_url('admin/actualizar_zona/' . $zona['idZona']) ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre de la zona
            </label>

            <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= old('nombre', $zona['nombre']) ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="invalid-feedback">
                    <?= session('errors.nombre') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">
                Costo de envío
            </label>

            <input type="number" step="0.01" class="form-control <?= session('errors.costoEnvio') ? 'is-invalid' : '' ?>" id="costoEnvio" name="costoEnvio" value="<?= old('costoEnvio', $zona['costoEnvio']) ?>">

            <?php if (session('errors.costoEnvio')): ?>
                <div class="invalid-feedback">
                    <?= session('errors.costoEnvio') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="<?= base_url('admin/zonas') ?>" class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="btn-guardar-producto">
                <i class="bi bi-check-lg"></i>
                Guardar cambios
            </button>

        </div>

    </form>

</div>

<?= $this->endSection() ?>