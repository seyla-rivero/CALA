<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\PromocionModel;
use App\Models\ItemPedidoModel;


class Home extends BaseController
{
    public function index(): string
    {

        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();

        $data['promociones'] = $promocionModel->obtenerPromociones();

        $data['promoDelDia'] = $promocionModel->obtenerPromoDelDia();

        $data['masVendida'] = $itemPedidoModel->find(31);

        return view('cliente/paginaPrincipal', $data);
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
