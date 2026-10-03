<?php

namespace App\Controllers;

use App\Models\PromocionModel;
use App\Models\ItemPedidoModel;

class Promocion extends BaseController
{
    public function promociones()
    {
        $promocionesModel = new PromocionModel();

        $datos['promociones'] = $promocionesModel->obtenerPromociones();

        return view('administrador/promociones', $datos);
    } 
    public function agregarPromocion()
    {
        return view('administrador/agregar_promocion');
    }

    public function guardarPromocion()
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Datos del formulario
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');
        $precio = $this->request->getPost('precio');

        $incluyeBebida = $this->request->getPost('incluyeBebida') ? 1 : 0;
        $incluyeEmpanadas = $this->request->getPost('incluyeEmpanadas') ? 1 : 0;

        // Imagen
        $imagen = $this->request->getFile('urlImagen');

        // Guardamos primero el item
        $datosItem = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'activo' => 1,
        ];

        // Si hay imagen, la guardamos
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {

            $nombreImagen = $imagen->getRandomName();

            $imagen->move(FCPATH . 'img', $nombreImagen);

            $datosItem['urlImagen'] = $nombreImagen;
        }

        // Insertamos en item_pedido
        $idItem = $itemPedidoModel->insert($datosItem);

        // Creamos la promoción relacionada
        $promocionModel->insert([
            'idItem' => $idItem,
            'esPromoDia' => 0,
            'esMasVendida' => 0,
            'incluyeBebida' => $incluyeBebida,
            'incluyeEmpanadas' => $incluyeEmpanadas
        ]);

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'La promoción se agregó correctamente.');
    }

    public function guardarMasVendida()
    {
        $promocionesModel = new PromocionModel();

        $idItem = $this->request->getPost('masVendida');

        $promocionesModel
            ->where('idItem >', 0)
            ->set('esMasVendida', 0)
            ->update();

        if (!empty($idItem)) {
            $promocionesModel
                ->where('idItem', $idItem)
                ->set('esMasVendida', 1)
                ->update();
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'La promoción se guardó correctamente como la más vendida.');
    }
    public function guardarPromoDelDia()
    {
        $promocionesModel = new PromocionModel();

        $promosSeleccionadas = $this->request->getPost('promoDelDia');

        $promocionesModel
            ->where('idItem >', 0)
            ->set('esPromoDia', 0)
            ->update();

        if (!empty($promosSeleccionadas)) {

            foreach ($promosSeleccionadas as $idItem) {

                $promocionesModel
                    ->where('idItem', $idItem)
                    ->set('esPromoDia', 1)
                    ->update();
            }
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Las promociones del día se guardaron correctamente.');
    }
}    