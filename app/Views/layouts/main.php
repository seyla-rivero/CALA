<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALA Delivery Sandwich</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('css/paginaPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/modalLoginRegistro.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/menu.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/menu.css') ?>">
</head>
<body>

<?= $this->include('componentes/header') ?>
<?= $this->renderSection('contenido') ?>
<?= $this->include('componentes/modalLogin') ?>
<?= $this->include('componentes/modalRegistro') ?>
<?= $this->include('componentes/footer') ?>

<?php if(session('modal') === 'registro'): ?> 
<script> document.addEventListener("DOMContentLoaded", function() {
    let modal = new bootstrap.Modal( document.getElementById('loginRegistro') );
    modal.show(); });
</script>
<?php endif; ?> <?php if(session('modal') === 'login'): ?> 
<script> document.addEventListener("DOMContentLoaded", function() {
let modal = new bootstrap.Modal( document.getElementById('loginModal') );
modal.show(); }); 
</script> 
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>