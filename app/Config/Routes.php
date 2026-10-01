<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('validar-login', 'Autenticacion::validarLogin');
$routes->post('validar-registro', 'Autenticacion::validarRegistro');
$routes->get('logout', 'Autenticacion::logout');
$routes->get('menu', 'Home::menu');
$routes->get('promociones', 'Home::promocion');
$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/aumentar-cantidad', 'Carrito::aumentarCantidad');
$routes->post('carrito/disminuir-cantidad', 'Carrito::disminuirCantidad');
$routes->get('carrito/cantidad', 'Carrito::cantidadCarrito');
$routes->get('checkout', 'Carrito::checkout');
$routes->post('carrito/confirmar-pedido', 'Carrito::confirmarPedido');
$routes->post('enviar-codigo-recuperacion', 'Autenticacion::enviarCodigoRecuperacion');

$routes->get('admin', 'Administrador::login');
$routes->post('admin/validarLogin', 'Administrador::validarLogin');
$routes->get('admin/panel', 'Administrador::panel');
$routes->get('admin/productos', 'Producto::index');
$routes->get('admin/agregar_producto', 'Producto::agregarProducto');
$routes->post('admin/guardar', 'Producto::guardar');
$routes->get('admin/editar_producto/(:num)', 'Producto::editarProducto/$1');
$routes->post('admin/actualizar_producto/(:num)', 'Producto::actualizarProducto/$1');
$routes->get('admin/editar_imagen/(:num)', 'Producto::editarImagen/$1');
$routes->post('admin/actualizar_imagen/(:num)', 'Producto::actualizarImagen/$1');
$routes->post('admin/desactivar_producto/(:num)', 'Producto::desactivarProducto/$1');
$routes->post('admin/activar_producto/(:num)', 'Producto::activarProducto/$1');