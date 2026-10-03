<?= $this->extend('layouts/admin') ?>
<?= $this->section('contenido') ?>
<div class="titulo-productos">
    <div>
        <h1>Agregar promoción</h1>
        <p>Creá una nueva promoción para CALA</p>
    </div>

    <a href="<?= base_url('admin/promociones') ?>" class="btn-volver">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>
</div>

<div class="formulario-producto">
    <form action="<?= base_url('admin/guardar_promocion') ?>" method="POST" enctype="multipart/form-data">

        <div class="mb-4">

            <label for="nombre" class="form-label">
                Nombre de la promoción
            </label>

            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Hamburguesa super + papas" value="<?= old('nombre') ?>"required>
        </div>

        <div class="mb-4">

            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Ingresá los ingredientes o detalles de la promoción"><?= old('descripcion') ?></textarea>

        </div>

        <div class="mb-4">

            <label for="precio" class="form-label">
                Precio
            </label>

            <input type="number" class="form-control" id="precio" name="precio" placeholder="Ej: 8500" min="0"step="0.01"value="<?= old('precio') ?>"required>

        </div>

        <div class="mb-4">

            <label for="urlImagen" class="form-label">
                Imagen
            </label>

            <input type="file" class="form-control" id="urlImagen" name="urlImagen" accept="image/*" required>

            <small class="text-muted">
                Formatos permitidos: JPG, JPEG, PNG y WEBP.
            </small>

        </div>

        <div class="mb-4">

            <label class="form-label">
                ¿Qué incluye la promoción?
            </label>

            <div class="form-check">

                <input class="form-check-input" type="checkbox" id="incluyeBebida" name="incluyeBebida" value="1"<?= old('incluyeBebida') ? 'checked' : '' ?>>

                <label class="form-check-label" for="incluyeBebida">
                    Incluye bebida
                </label>

            </div>

            <div class="form-check">

                <input class="form-check-input" type="checkbox" id="incluyeEmpanadas" name="incluyeEmpanadas" value="1" <?= old('incluyeEmpanadas') ? 'checked' : '' ?>>

                <label class="form-check-label" for="incluyeEmpanadas">
                    Incluye empanadas
                </label>

            </div>

        </div>

        <div class="acciones-formulario">

            <a href="<?= base_url('admin/promociones') ?>" class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="btn-guardar-producto">
                <i class="bi bi-check-lg"></i>
                Guardar promoción
            </button>

        </div>

    </form>

</div>

<?= $this->endSection() ?>