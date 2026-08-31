<?php $errors = session()->getFlashdata('erroresLogin') ?? []; ?>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-cala">
            <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
            </div>

            <img src="<?= base_url('img/logo Cala.png') ?>" class="logo-login d-block mx-auto mb-4" alt="Logo CALA">
            
            <h4 class="titulo-login text-center mt-3">
                Iniciá sesión
            </h4>
            
            <div class="modal-body">
                <form method="post" action="<?= site_url('validar-login') ?>"><?= csrf_field() ?>
                    <div class="mb-3">
                         <label class="form-label text-white">
                            <i class="bi bi-envelope-fill"></i>
                            Correo electrónico
                        </label>
                        <input type="email" name="email" class="form-control input-cala" placeholder="Correo electrónico">
                        <?php if(isset($errors['email'])): ?>
                            <small class="text-danger">
                                <?= $errors['email'] ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-0 position-relative">
                        <label class="form-label text-white">
                            <i class="bi bi-lock-fill"></i>
                            Contraseña
                        </label>
                        <input type="password" name="password" id="loginPassword" class="form-control input-cala" placeholder="Contraseña">
                        <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('loginPassword',  this)"></i>
                        <?php if(isset($errors['password'])): ?>
                            <small class="text-danger">
                                <?= $errors['password'] ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="text-end mt-1">
                        <a href="#" class="link-cala">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="btn boton-login w-100 mt-3">
                        Ingresar
                    </button>
                </form>
            </div>
            <div class="text-center mt-3">
                <p>¿No tenés cuenta?
                    <a href="#" class="link-cala" data-bs-toggle="modal" data-bs-target="#loginRegistro">
                        Registrate
                    </a>
                </p>
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
<script>
document.getElementById('loginModal').addEventListener('hidden.bs.modal', function () {

    this.querySelectorAll('.text-danger').forEach(function (error) {
        error.remove();
    });

    this.querySelector('form').reset();
});
</script>
