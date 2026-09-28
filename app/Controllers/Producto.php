<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class Producto extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();

        $datos['productos'] = $productoModel->obtenerTodosLosProductos();

        return view('administrador/productos', $datos);
    }

    public function agregarProducto()
    {
        return view('administrador/agregar_producto');
    }
}