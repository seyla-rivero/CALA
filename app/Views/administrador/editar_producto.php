<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $producto */ ?>
<?php /** @var array $categorias */ ?>

<div class="titulo-productos">

    <div>
        <h1>Editar producto</h1>
        <p>Modificá los datos del producto</p>
    </div>

</div>

<div class="formulario-producto">

    <form action="<?= site_url('admin/actualizar_producto/' . $producto['idItem']) ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre del producto
            </label>

            <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= esc(old('nombre', $producto['nombre'])) ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="invalid-feedback">
                    <?= esc(session('errors.nombre')) ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="mb-3">
            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea class="form-control <?= session('errors.descripcion') ? 'is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="4"
            ><?= esc(old('descripcion', $producto['descripcion'])) ?></textarea>

            <?php if (session('errors.descripcion')): ?>
                <div class="invalid-feedback">
                    <?= esc(session('errors.descripcion')) ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="mb-3">
            <label for="precio" class="form-label">
                Precio
            </label>

            <input type="number" step="0.01" class="form-control <?= session('errors.precio') ? 'is-invalid' : '' ?>" id="precio" name="precio" value="<?= esc(old('precio', $producto['precio'])) ?>">

            <?php if (session('errors.precio')): ?>
                <div class="invalid-feedback">
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
                        <?= old('idCategoria', $producto['idCategoria']) == $categoria['idCategoria'] ? 'selected' : '' ?>
                    >
                        <?= esc($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (session('errors.idCategoria')): ?>
                <div class="invalid-feedback">
                    <?= esc(session('errors.idCategoria')) ?>
                </div>
            <?php endif; ?>
           
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="<?= base_url('admin/productos') ?>" class="btn-cancelar">
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