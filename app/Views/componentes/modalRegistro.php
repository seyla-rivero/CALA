<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<div class="modal fade" id="loginRegistro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-cala">
            <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
            </div>
            
            <img src="<?= base_url('img/logo Cala.png') ?>" class="logo-login logo-login d-block mx-auto mb-4 " alt="Logo CALA">
            
            <h4 class="titulo-login text-center mt-3">
                Crea tu cuenta
            </h4>
           
            <div class="modal-body">
                <form method="post" action="<?= site_url('validar-registro') ?>"><?= csrf_field() ?>
                    <div class="mb-3">
                         <label class="form-label text-white">
                            <i class="bi bi-person"></i>
                            Nombre
                        </label>
                        <input type="text" name="nombre" class="form-control input-cala" value="<?= old('nombre') ?>" placeholder="Nombre">
                        <?php if(session('errores.nombre')): ?>
                            <small class="text-danger">
                                <?= session('errores.nombre') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                         <label class="form-label text-white">
                            <i class="bi bi-person"></i>
                            Apellido
                        </label>
                        <input type="text" name="apellido" class="form-control input-cala" value="<?= old('apellido') ?>" placeholder="Apellido">
                        <?php if(session('errores.apellido')): ?>
                            <small class="text-danger">
                                <?= session('errores.apellido') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                     <div class="mb-3">
                         <label class="form-label text-white">
                            <i class="bi bi-telephone-fill"></i>
                            Teléfono
                        </label>
                        <input type="text" name="telefono" class="form-control input-cala" value="<?= old('telefono') ?>" placeholder="Telefono">
                        <?php if(session('errores.telefono')): ?>
                            <small class="text-danger">
                                <?= session('errores.telefono') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                         <label class="form-label text-white">
                            <i class="bi bi-envelope-fill"></i>
                            Correo electrónico
                        </label>
                        <input type="email" name="email" class="form-control input-cala" value="<?= old('email') ?>" placeholder="Correo electrónico">
                        <?php if(session('errores.email')): ?>
                            <small class="text-danger">
                                <?= session('errores.email') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="form-label text-white">
                            <i class="bi bi-lock-fill"></i>
                            Contraseña
                        </label>
                        <input type="password" name="password" id="registroPassword" class="form-control input-cala" placeholder="Contraseña">
                        <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('registroPassword',  this)"></i>
                        <?php if(session('errores.password')): ?>
                            <small class="text-danger">
                                <?= session('errores.password') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                     <div class="mb-4 position-relative">
                        <label class="form-label text-white">
                            <i class="bi bi-lock-fill"></i>
                            Confirmar contraseña
                        </label>
                        <input type="password" name="confirmar" id="confirmPassword" class="form-control input-cala" placeholder="Contraseña">
                        <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('confirmPassword',  this)"></i>
                        <?php if(session('errores.confirmar')): ?>
                            <small class="text-danger">
                                <?= session('errores.confirmar') ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn boton-login w-100 mb-2">
                        Registrarse
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
function togglePassword(id, icon) {
    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>