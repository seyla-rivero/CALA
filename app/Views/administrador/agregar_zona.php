<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<div class="titulo-productos">

    <div>
        <h1>Agregar zona</h1>
        <p>Completá los datos de la nueva zona</p>
    </div>

    <a href="<?= base_url('admin/zonas') ?>" class="btn-volver">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

</div>
<div class="formulario-producto">

    <form action="<?= site_url('admin/guardar_zona') ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre de la zona
            </label>

            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Zona norte" value="<?= old('nombre') ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.nombre') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="costoEnvio" class="form-label">
                Costo de envío
            </label>

            <input type="number" class="form-control" id="costoEnvio" name="costoEnvio" step="0.01" min="0" placeholder="Ej: 8500" value="<?= old('costoEnvio') ?>">

            <?php if (session('errors.costoEnvio')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.costoEnvio') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="acciones-formulario">

            <a href="<?= base_url('admin/zonas') ?>" class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="btn-guardar-producto">
                <i class="bi bi-check-lg"></i>
                Guardar zona
            </button>

        </div>

    </form>

</div>

<?= $this->endSection() ?>