<?php

namespace App\Controllers;

use App\Models\ItemPedidoModel;

class Carrito extends BaseController
{
    public function index()
    {
        $session = session();

        $carrito = $session->get('carrito') ?? [];

        $total = 0;

        foreach ($carrito as $item) {

            $total += $item['precio'] * $item['cantidad'];

        }

        return view('cliente/carrito', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }

    public function agregar()
    {
        // Obtener los datos enviados desde JavaScript
        $datos = $this->request->getJSON(true);

        $idItem = $datos['idItem'] ?? null;
        $cantidad = $datos['cantidad'] ?? 1;
        $comentario = trim($datos['comentario'] ?? '');

        // Verificar que exista el producto
        if (!$idItem) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se recibió el producto.'
            ]);
        }

        $itemPedidoModel = new ItemPedidoModel();

        $item = $itemPedidoModel
            ->where('idItem', $idItem)
            ->where('activo', 1)
            ->first();

        // Si no existe o está inactivo
        if (!$item) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El producto no está disponible.'
            ]);
        }

        // Obtener el carrito actual de la sesión
        $session = session();

        $carrito = $session->get('carrito') ?? [];

        // Si el producto ya está en el carrito
        if (isset($carrito[$idItem])) {

            $carrito[$idItem]['cantidad'] += $cantidad;

            // Actualizar comentario si se escribió uno nuevo
            if ($comentario !== '') {
                $carrito[$idItem]['comentario'] = $comentario;
            }

        } else {

            // Agregar nuevo producto
            $carrito[$idItem] = [
                'idItem' => $item['idItem'],
                'nombre' => $item['nombre'],
                'precio' => $item['precio'],
                'urlImagen' => $item['urlImagen'],
                'cantidad' => $cantidad,
                'comentario' => $comentario
            ];
        }

        // Guardar carrito en la sesión
        $session->set('carrito', $carrito);

        return $this->response->setJSON([
            'ok' => true,
            'mensaje' => 'Producto agregado al pedido.'
        ]);
    }
}