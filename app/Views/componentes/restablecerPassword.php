
<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<?php /** @var string $token */ ?>

<div class="restablecer-portada">

    <div class="restablecer-formulario">
        <div class="modal-content modal-cala p-4">

            <img src="<?= base_url('img/logo Cala.png') ?>"
                 class="logo-login d-block mx-auto mb-3"
                 alt="Logo CALA">

            <h3 class="titulo-login text-center mb-4">
                Restablecer contraseña
            </h3>

            <form method="post" action="<?= site_url('actualizar-password') ?>">
                <?= csrf_field() ?>

                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <div class="mb-3 position-relative">
                    <label class="form-label text-white">
                        Nueva contraseña
                    </label>

                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control input-cala"
                           placeholder="Ingresá tu nueva contraseña">

                    <i class="bi bi-eye toggle-eye"
                       id="ojoPassword"
                       onclick="mostrarPassword('password', 'ojoPassword')"></i>
                       <?php if (!empty($erroresPassword['password'])): ?>
                            <small class="text-danger d-block mt-1">
                                <?= esc($erroresPassword['password']) ?>
                            </small>
                        <?php endif; ?>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label text-white">
                        Confirmar contraseña
                    </label>

                    <input type="password"
                           id="confirmar"
                           name="confirmar"
                           class="form-control input-cala"
                           placeholder="Repetí tu nueva contraseña">

                    <i class="bi bi-eye toggle-eye"
                       id="ojoConfirmar"
                       onclick="mostrarPassword('confirmar', 'ojoConfirmar')"></i>
                       
                    <?php if (!empty($erroresPassword['confirmar'])): ?>
                        <small class="text-danger d-block mt-1">
                            <?= esc($erroresPassword['confirmar']) ?>
                        </small>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn boton-login w-100 mt-3">
                    Guardar nueva contraseña
                </button>

            </form>
        </div>
    </div>

</div>

<script>
    function mostrarPassword(idCampo, idOjo) {
        const campo = document.getElementById(idCampo);
        const ojo = document.getElementById(idOjo);

        if (campo.type === 'password') {
            campo.type = 'text';
            ojo.classList.remove('bi-eye');
            ojo.classList.add('bi-eye-slash');
        } else {
            campo.type = 'password';
            ojo.classList.remove('bi-eye-slash');
            ojo.classList.add('bi-eye');
        }
    }
</script>

<?= $this->endSection() ?>
