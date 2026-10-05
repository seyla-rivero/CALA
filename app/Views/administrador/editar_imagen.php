<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<?php /** @var array $producto */ ?>
<?php /** @var array $tipo */ ?>

<div class="titulo-productos">

    <div>
        <h1>Modificar imagen</h1>
        <p>
            Actualizá la imagen del <?= $tipo === 'promocion' ? 'promoción' : 'producto' ?>
        </p>
    </div>

</div>

<div class="formulario-producto">

    <form action="<?= $tipo === 'promocion'
        ? site_url('admin/actualizar_imagen_promocion/' . $producto['idItem'])
        : site_url('admin/actualizar_imagen/' . $producto['idItem'])
    ?>"
    method="post"
    enctype="multipart/form-data">

        <div class="mb-4">

            <label class="form-label">
                <?= $tipo === 'promocion' ? 'Promoción' : 'Producto' ?>
            </label>

            <input type="text" class="form-control" value="<?= esc($producto['nombre']) ?>" disabled>

        </div>

        <div class="mb-4">

            <label class="form-label">
                Imagen
            </label>

            <div class="text-center">

                <?php if (!empty($producto['urlImagen'])): ?>

                    <img id="vistaPrevia"
                         src="<?= base_url('img/' . $producto['urlImagen']) ?>"
                         alt="<?= esc($producto['nombre']) ?>"
                         class="img-fluid"
                         style="max-width: 300px; max-height: 250px; object-fit: contain;">

                <?php else: ?>

                    <img id="vistaPrevia"
                         src=""
                         alt="Vista previa"
                         class="img-fluid d-none"
                         style="max-width: 300px; max-height: 250px; object-fit: contain;">

                    <p id="sinImagen" class="text-muted">
                        Este <?= $tipo === 'promocion' ? 'promoción' : 'producto' ?>
                        no tiene una imagen cargada.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <div class="mb-3">

            <label for="imagen" class="form-label">
                Nueva imagen
            </label>

            <input type="file"
                   class="form-control <?= session('errors.imagen') ? 'is-invalid' : '' ?>"
                   id="imagen"
                   name="imagen"
                   accept=".jpg,.jpeg,.png,.webp">

            <?php if (session('errors.imagen')): ?>

                <div class="invalid-feedback">
                    <?= session('errors.imagen') ?>
                </div>

            <?php endif; ?>

            <div class="form-text">
                Formatos permitidos: JPG, JPEG, PNG o WEBP. Tamaño máximo: 3 MB.
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="<?= $tipo === 'promocion'
                ? base_url('admin/promociones')
                : base_url('admin/productos')
            ?>"
            class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="btn-guardar-producto">
                <i class="bi bi-check-lg"></i>
                Guardar imagen
            </button>

        </div>

    </form>

</div>

<script>

document.getElementById('imagen').addEventListener('change', function(event) {

    const archivo = event.target.files[0];
    const vistaPrevia = document.getElementById('vistaPrevia');
    const sinImagen = document.getElementById('sinImagen');

    if (archivo) {

        const url = URL.createObjectURL(archivo);

        vistaPrevia.src = url;
        vistaPrevia.classList.remove('d-none');

        if (sinImagen) {
            sinImagen.classList.add('d-none');
        }

    }

});

</script>

<?= $this->endSection() ?>