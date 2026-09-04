<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;


class Home extends BaseController
{
    public function index(): string{
        return view('cliente/paginaPrincipal');
    }
    public function menu(): string{

        $productoModel = new ProductoModel();
        $categoriaModel = new CategoriaModel();

        $data['productos'] = $productoModel->obtenerProductosMenu();
        $data['categorias'] = $categoriaModel->findAll();

        return view('cliente/menu', $data);
    }
    
}
