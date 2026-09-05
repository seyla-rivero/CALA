<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\PromocionModel;


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

    public function promocion(): string{

        $promocionModel = new PromocionModel();

        $data['productos'] = $promocionModel->obtenerPromociones();

        return view('cliente/promociones', $data);
    }
    
}
