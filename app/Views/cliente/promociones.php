<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?php /** @var array $categorias */ ?>
<?php /** @var array $productos */ ?>

<section class="menu-section">

    <div class="menu-header">
        <h1>Promociones</h1>
        <p>¡Aprovechá nuestras promos!</p>
    </div>
  
    <?php if (empty($haySucursalAbierta)): ?>
        <div class="aviso-cala-cerrado mt-4" role="alert">
            <div class="aviso-cala-icono">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="aviso-cala-contenido">
                <h5>¡En este momento estamos cerrados!</h5>
                <p>
                    Podés consultar nuestro menú y descubrir las promociones.
                    Para realizar tu pedido, volvé durante nuestro horario de atención.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($productos)): ?>
    
        <div class="productos-grid mt-3">

            <?php foreach ($productos as $producto): ?>

                <div class="producto-card">

                    <img
                        src="<?= base_url('img/' . $producto['urlImagen']) ?>"
                        alt="<?= esc($producto['nombre']) ?>">

                    <div class="producto-info">

                        <h3>
                            <?= esc($producto['nombre']) ?>
                        </h3>

                        <span class="precio">
                            $<?= number_format($producto['precio'], 0, ',', '.') ?>
                        </span>

                        <button
                            class="btn-ver"
                            onclick="abrirModal(
                                <?= $producto['idItem'] ?>,
                                '<?= esc($producto['nombre'], 'js') ?>',
                                '<?= esc($producto['descripcion'], 'js') ?>',
                                <?= $producto['precio'] ?>,
                                '<?= base_url('img/' . $producto['urlImagen']) ?>',
                                <?= $producto['incluyeBebida'] ?>,
                                <?= $producto['incluyeEmpanadas'] ?>
                            )">
                            Ver más
                        </button>

                    </div>
                </div>

            <?php endforeach; ?>            
        </div>
    <?php else: ?>

        <div class="mensaje-menu-vacio">
            <h3>¡Pronto tendremos nuevas promociones!</h3>
            <p>En este momento no hay promociones disponibles.</p>
            <p>Volvé a consultar pronto.</p>
        </div>

    <?php endif; ?>    
</section>    
<!--Modal detalle-->
<div id="modalProducto" class="modal-producto">

    <div class="modal-contenido">

        <button class="cerrar-modal" onclick="cerrarModal()">
            &times;
        </button>

        <img id="modalImagen" src="" alt="Producto">
    
        <div class="modal-info">

            <h2 id="modalNombre"></h2>

            <p id="modalDescripcion"></p>

            <span id="modalPrecio" class="modal-precio"></span>

            <div id="contenedorBebida" class="bebida-producto" style="display: none;">

                <select id="bebida" class="form-select">
                    <option value="">Elegí tu bebida</option>
                    <option value="Talca cola 3 litros">Talca cola 3 litros</option>
                    <option value="Talca lima 3 litros">Talca lima 3 litros</option>
                    <option value="Talca naranja 3 litros">Talca naranja 3 litros</option>
                    <option value="Talca pomelo 3 litros">Talca pomelo 3 litros</option>
                </select>

            </div>

            <div id="contenedorEmpanadas" class="empanadas-producto" style="display: none;">

                <select id="empanadas" class="form-select">
                    <option value="">Elegí empanadas</option>
                    <option value="Criollas">Criollas</option>
                    <option value="Jamon y queso">Jamón y queso</option>
                </select>

            </div>

            <div class="cantidad">

                <span class="cantidad-label">Cantidad:</span>

                <button type="button" onclick="disminuirCantidad()">−</button>

                <span id="cantidad" class="cantidad-numero">1</span>

                <button type="button" onclick="aumentarCantidad()">+</button>

            </div>

             <div class="comentario-producto">
                <label for="comentario">Comentario:</label>

                <textarea id="comentario" name="comentario" placeholder="Ej.: Sin mayonesa, sin cebolla..." maxlength="200"></textarea>
            </div>
            
            <button type="button" id="btnAgregarPedido" class="btn-agregar mt-3" onclick="agregarAlCarrito()">
                <?= empty($haySucursalAbierta)
                    ? 'Sucursal cerrada'
                    : 'Agregar al pedido' ?>
            </button>

        </div>

    </div>

</div>
<!-- Modal producto agregado -->
<div class="modal fade" id="productoAgregadoModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modal-cala">

            <div class="modal-body text-center p-4">

                <i class="bi bi-check-circle-fill text-success"
                   style="font-size: 4rem;">
                </i>

                <h3 class="mt-3">¡Producto agregado!</h3>

                <p>
                    El producto fue agregado correctamente a tu pedido.
                </p>

                <button type="button" class="btn boton-login mt-3" data-bs-dismiss="modal">
                    Aceptar
                </button>

            </div>

        </div>

    </div>

</div>
<!-- Modal: sucursal cerrada -->
<div class="modal fade" id="sucursalCerradaModal" tabindex="-1"
     aria-labelledby="sucursalCerradaTitulo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-sucursal-cerrada">

            <div class="modal-body text-center p-4">

                <div class="icono-sucursal-cerrada mb-3">
                    <i class="bi bi-clock-history"></i>
                </div>

                <h5 class="titulo-sucursal-cerrada mb-3"
                    id="sucursalCerradaTitulo">
                    ¡En este momento estamos cerrados!
                </h5>

                <p class="mensaje-sucursal-cerrada mb-4">
                    Podés consultar nuestro menú y elegir tus productos.
                    Volvé durante nuestro horario de atención para
                    realizar tu pedido.
                </p>

                <button type="button"
                        class="btn btn-sucursal-cerrada"
                        data-bs-dismiss="modal">
                    Entendido
                </button>

            </div>

        </div>
    </div>
</div>

<script>
    const comprasHabilitadas =
        <?= !empty($haySucursalAbierta) ? 'true' : 'false' ?>;
</script>
<?= $this->endSection() ?>