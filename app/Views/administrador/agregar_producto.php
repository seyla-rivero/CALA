<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

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

    <form action="<?= base_url('admin/productos/guardar') ?>" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre del producto
            </label>

            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Lomo completo" value="<?= old('nombre') ?>">

            <?php if (session('errors.nombre')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.nombre') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Ingresá los ingredientes o descripción del producto"><?= old('descripcion') ?></textarea>

            <?php if (session('errors.descripcion')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.descripcion') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">
                Precio
            </label>

            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" placeholder="Ej: 8500" value="<?= old('precio') ?>">

            <?php if (session('errors.precio')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.precio') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="idCategoria" class="form-label">
                Categoría
            </label>

            <select class="form-select" id="idCategoria" name="idCategoria">
                <option value="">Seleccioná una categoría</option>

                <option value="1">Hamburguesas</option>
                <option value="2">Lomos</option>
                <option value="3">Milanesas</option>
                <option value="4">Pizzas</option>
                <option value="5">Empanadas</option>
                <option value="6">Panchos</option>
                <option value="7">Bebidas</option>
                <option value="8">Papas</option>
            </select>

            <?php if (session('errors.idCategoria')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.idCategoria') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="imagen" class="form-label">
                Imagen del producto
            </label>

            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">

            <small class="text-muted">
                Formatos permitidos: JPG, JPEG, PNG o WEBP.
            </small>

            <?php if (session('errors.imagen')): ?>
                <div class="text-danger mt-1">
                    <?= session('errors.imagen') ?>
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