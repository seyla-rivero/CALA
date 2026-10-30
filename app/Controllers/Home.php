<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\PromocionModel;
use App\Models\ItemPedidoModel;
use App\Models\SucursalModel;
use App\Models\HorarioSucursalModel;


class Home extends BaseController
{
    private function obtenerDatosNotificaciones(): array
    {
        $session = session();

        if (!$session->get('logueado') || !$session->get('idCliente')) {
            return [
                'notificaciones' => [],
                'cantidadNotificacionesNoLeidas' => 0,
            ];
        }

        $idCliente = $session->get('idCliente');
        $db = \Config\Database::connect();

        $notificaciones = $db
            ->table('notificacion_cliente')
            ->where('idCliente', $idCliente)
            ->orderBy('fecha', 'DESC')
            ->get()
            ->getResultArray();

        $cantidadNoLeidas = $db
            ->table('notificacion_cliente')
            ->where('idCliente', $idCliente)
            ->where('leida', 0)
            ->countAllResults();

        return [
            'notificaciones' => $notificaciones,
            'cantidadNotificacionesNoLeidas' => $cantidadNoLeidas,
        ];
    }

    public function index(): string
    {

        $promocionModel = new PromocionModel();
        
        $sucursalModel = new SucursalModel();

        $horarioSucursalModel = new HorarioSucursalModel(); 

        $data['promociones'] = $promocionModel->obtenerPromociones();

        $data['promoDelDia'] = $promocionModel->obtenerPromoDelDia();

        $data['sucursales'] = $sucursalModel->where('activo', 1)->findAll();

        $data['masVendida'] = $promocionModel->obtenerMasVendida();

        $data['haySucursalAbierta'] = $horarioSucursalModel->haySucursalAbierta();

        $data = array_merge($data, $this->obtenerDatosNotificaciones());

        return view('cliente/paginaPrincipal', $data);
    }

    public function menu(): string{

        $productoModel = new ProductoModel();
        $categoriaModel = new CategoriaModel();
        $horarioSucursalModel = new HorarioSucursalModel(); 

        $data['productos'] = $productoModel->obtenerProductosMenu();
        $data['categorias'] = $categoriaModel->findAll();
        $data['haySucursalAbierta'] = $horarioSucursalModel->haySucursalAbierta();

        $data = array_merge($data, $this->obtenerDatosNotificaciones());

        return view('cliente/menu', $data);
    }

    public function promocion(): string{

        $promocionModel = new PromocionModel();
        $horarioSucursalModel = new HorarioSucursalModel(); 

        $data['productos'] = $promocionModel->obtenerPromociones();

        $data['haySucursalAbierta'] = $horarioSucursalModel->haySucursalAbierta();

        $data = array_merge($data, $this->obtenerDatosNotificaciones());

        return view('cliente/promociones', $data);
    }
    
}
