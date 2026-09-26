<?= $this->extend('layouts/admin') ?>

<?= $this->section('contenido') ?>

<?php $errors = session()->getFlashdata('erroresLoginAdmin') ?? []; ?>

<div class="login-admin">

    <div class="login-admin-card">

        <div class="login-admin-logo">
            <img src="<?= base_url('img/logo Cala.png') ?>" alt="CALA Delivery Sandwich">
        </div>

        <div class="login-admin-header">

            <h1>Bienvenido</h1>

            <p>Acceso al panel de administración</p>

        </div>

        <form method="post" action="<?= site_url('admin/validarLogin') ?>" novalidate ><?= csrf_field() ?>

            <div class="admin-input-group">

                <label for="email">
                    <i class="bi bi-envelope-fill"></i>
                    Correo electrónico
                </label>

                <div class="admin-input">

                    <i class="bi bi-envelope"></i>

                    <input type="email" id="email" name="email" placeholder="Ingresá tu correo" value="<?= old('email') ?>" autocomplete="email">
                </div>

                <?php if (isset($errors['email'])): ?>
                    <small class="admin-error">
                        <?= $errors['email'] ?>
                    </small>
                <?php endif; ?>

            </div>

            <div class="admin-input-group">

                <label for="adminPassword">
                    <i class="bi bi-lock-fill"></i>
                    Contraseña
                </label>

                <div class="admin-input">

                    <i class="bi bi-lock"></i>

                    <input type="password" id="adminPassword" name="password" placeholder="Ingresá tu contraseña" autocomplete="current-password"
                    >

                    <button type="button" class="toggle-password" onclick="togglePassword('adminPassword', this)" aria-label="Mostrar contraseña">
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

                <?php if (isset($errors['password'])): ?>

                    <small class="admin-error">
                        <?= $errors['password'] ?>
                    </small>

                <?php endif; ?>

            </div>

            <button type="submit" class="btn-login-admin">

                Ingresar al panel

                <i class="bi bi-arrow-right"></i>

            </button>

        </form>

        <div class="login-admin-footer">

            <i class="bi bi-shield-lock-fill"></i>

            <span>Acceso exclusivo para administradores</span>

        </div>

    </div>

</div>


<script>

function togglePassword(id, button) {

    const input = document.getElementById(id);
    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');

    }

}

</script>

<?= $this->endSection() ?>