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