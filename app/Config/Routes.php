<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('validar-login', 'Autenticacion::validarLogin');
$routes->post('validar-registro', 'Autenticacion::validarRegistro');
