<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $producto */ ?>

<div class="titulo-productos">

    <div>
        <h1>Editar promoción</h1>
        <p>Modificá los datos de la promoción</p>
    </div>

</div>

<div class="formulario-producto">

    <form action="<?= site_url('admin/actualizar_promocion/' . $producto['idItem']) ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre de la promoción
            </label>

            <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= old('nombre', $producto['nombre']) ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="invalid-feedback">
                    <?= session('errors.nombre') ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="mb-3">
            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea class="form-control <?= session('errors.descripcion') ? 'is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="4"
            ><?= old('descripcion', $producto['descripcion']) ?></textarea>

            <?php if (session('errors.descripcion')): ?>
                <div class="invalid-feedback">
                    <?= session('errors.descripcion') ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="mb-3">
            <label for="precio" class="form-label">
                Precio
            </label>

            <input type="number" step="0.01" class="form-control <?= session('errors.precio') ? 'is-invalid' : '' ?>" id="precio" name="precio" value="<?= old('precio', $producto['precio']) ?>">

            <?php if (session('errors.precio')): ?>
                <div class="invalid-feedback">
                    <?= session('errors.precio') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">

            <label class="form-label">
                ¿Qué incluye la promoción?
            </label>

            <div class="form-check">

                <input class="form-check-input" type="checkbox" id="incluyeBebida" name="incluyeBebida" value="1"
                <?= old('incluyeBebida', $producto['incluyeBebida']) ? 'checked' : '' ?>>

                <label class="form-check-label" for="incluyeBebida">
                    Incluye bebida
                </label>

            </div>

            <div class="form-check">

                <input class="form-check-input" type="checkbox" id="incluyeEmpanadas" name="incluyeEmpanadas" value="1"
                <?= old('incluyeEmpanadas', $producto['incluyeEmpanadas']) ? 'checked' : '' ?>>

                <label class="form-check-label" for="incluyeEmpanadas">
                    Incluye empanadas
                </label>

            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="<?= base_url('admin/promociones') ?>" class="btn-cancelar">
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