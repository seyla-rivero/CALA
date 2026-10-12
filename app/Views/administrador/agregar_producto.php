<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<?php /** @var array $categorias */ ?>

<div class="titulo-productos">

    <div>
        <h1>Agregar producto</h1>
        <p>Completá los datos del nuevo producto</p>
    </div>

    <a href="<?= base_url('admin/productos') ?>" class="btn-volver">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

</div>
<div class="formulario-producto">

    <form action="<?= site_url('admin/guardar') ?>" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre del producto
            </label>

            <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" id="nombre" name="nombre" placeholder="Ej: Lomo completo" value="<?= esc(old('nombre', '')) ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="text-danger mt-1">
                    <?= esc(session('errors.nombre')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea class="form-control <?= session('errors.descripcion') ? 'is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="4" placeholder="Ingresá los ingredientes o descripción del producto"><?= esc(old('descripcion', '')) ?></textarea>

            <?php if (session('errors.descripcion')): ?>
                <div class="text-danger mt-1">
                    <?= esc(session('errors.descripcion')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">
                Precio
            </label>

            <input type="number" class="form-control <?= session('errors.precio') ? 'is-invalid' : '' ?>" id="precio" name="precio" step="0.01" placeholder="Ej: 8500" value="<?= esc(old('precio', '')) ?>">

            <?php if (session('errors.precio')): ?>
                <div class="text-danger mt-1">
                    <?= esc(session('errors.precio')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="idCategoria" class="form-label">
                Categoría
            </label>

            <select
                class="form-select <?= session('errors.idCategoria') ? 'is-invalid' : '' ?>"
                id="idCategoria"
                name="idCategoria">
                <option value="">Seleccioná una categoría</option>

                <?php foreach ($categorias as $categoria): ?>
                    <option
                        value="<?= esc($categoria['idCategoria']) ?>"
                        <?= old('idCategoria') == $categoria['idCategoria'] ? 'selected' : '' ?>>
                        <?= esc($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (session('errors.idCategoria')): ?>
                <div class="text-danger mt-1">
                    <?= esc(session('errors.idCategoria')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="imagen" class="form-label">
                Imagen del producto
            </label>

            <input type="file" class="form-control <?= session('errors.imagen') ? 'is-invalid' : '' ?>" id="imagen" name="imagen" accept="image/*">

            <small class="text-muted">
                Formatos permitidos: JPG, JPEG, PNG o WEBP. Tamaño máximo: 3 MB.
            </small>

            <?php if (session('errors.imagen')): ?>
                <div class="text-danger mt-1">
                    <?= esc(session('errors.imagen')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="acciones-formulario">

            <a href="<?= base_url('admin/productos') ?>" class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="btn-guardar-producto">
                <i class="bi bi-check-lg"></i>
                Guardar producto
            </button>

        </div>

    </form>

</div>

<?= $this->endSection() ?>