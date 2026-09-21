<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\PromocionModel;
use App\Models\ItemPedidoModel;
use App\Models\SucursalModel;


class Home extends BaseController
{
    public function index(): string
    {

        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();
        $sucursalModel = new SucursalModel();

        $data['promociones'] = $promocionModel->obtenerPromociones();

        $data['promoDelDia'] = $promocionModel->obtenerPromoDelDia();

        $data['sucursales'] = $sucursalModel->where('activo', 1)->findAll();

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
