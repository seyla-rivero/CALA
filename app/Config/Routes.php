
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// Rutas del cliente

$routes->get('/', 'Home::index');
$routes->post('validar-login', 'Autenticacion::validarLogin');
$routes->post('validar-registro', 'Autenticacion::validarRegistro');
$routes->get('logout', 'Autenticacion::logout');

$routes->get('menu', 'Home::menu');
$routes->get('promociones', 'Home::promocion');

// Carrito
$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/aumentar-cantidad', 'Carrito::aumentarCantidad');
$routes->post('carrito/disminuir-cantidad', 'Carrito::disminuirCantidad');
$routes->get('carrito/cantidad', 'Carrito::cantidadCarrito');

$routes->get('checkout', 'Carrito::checkout');
$routes->post('carrito/confirmar-pedido', 'Carrito::confirmarPedido');


// Acceso del administrador

$routes->get('admin', 'Administrador::login');
$routes->post('admin/validarLogin', 'Administrador::validarLogin');

// Rutas protegidas del administrador

$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {

    // Panel principal
    $routes->get('panel', 'Administrador::panel');

    // Productos
    $routes->get('productos', 'Producto::index');
    $routes->get('agregar_producto', 'Producto::agregarProducto');
    $routes->post('guardar', 'Producto::guardarProducto');
    $routes->get('editar_producto/(:num)', 'Producto::editarProducto/$1');
    $routes->post('actualizar_producto/(:num)', 'Producto::actualizarProducto/$1');
    $routes->get('editar_imagen/(:num)', 'Producto::editarImagen/$1');
    $routes->post('actualizar_imagen/(:num)', 'Producto::actualizarImagen/$1');
    $routes->post('desactivar_producto/(:num)', 'Producto::desactivarProducto/$1');
    $routes->post('activar_producto/(:num)', 'Producto::activarProducto/$1');

    // Promociones
    $routes->get('promociones', 'Promocion::promociones');
    $routes->get('agregar_promocion', 'Promocion::agregarPromocion');
    $routes->post('guardar_promocion', 'Promocion::guardarPromocion');
    $routes->post('guardar_mas_vendida', 'Promocion::guardarMasVendida');
    $routes->post('guardar_promo_del_dia', 'Promocion::guardarPromoDelDia');
    $routes->get('editar_promocion/(:num)', 'Promocion::editarPromocion/$1');
    $routes->post('actualizar_promocion/(:num)', 'Promocion::actualizarPromocion/$1');
    $routes->get('editar_imagen_promocion/(:num)', 'Promocion::editarImagen/$1');
    $routes->post('actualizar_imagen_promocion/(:num)', 'Promocion::actualizarImagen/$1');
    $routes->post('desactivar_promocion/(:num)', 'Promocion::desactivarPromocion/$1');
    $routes->post('activar_promocion/(:num)', 'Promocion::activarPromocion/$1');

    // Zonas
    $routes->get('zonas', 'Zona::index');
    $routes->get('agregar_zona', 'Zona::agregarZona');
    $routes->post('guardar_zona', 'Zona::guardarZona');
    $routes->get('editar_zona/(:num)', 'Zona::editarZona/$1');
    $routes->post('actualizar_zona/(:num)', 'Zona::actualizarZona/$1');
    $routes->post('desactivar_zona/(:num)', 'Zona::desactivarZona/$1');
    $routes->post('activar_zona/(:num)', 'Zona::activarZona/$1');
});

// Cerrar sesion del administrador
$routes->get('logoutAdmi', 'Administrador::logoutAdmi');

