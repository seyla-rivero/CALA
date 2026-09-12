<?php

namespace App\Controllers;

use App\Models\ItemPedidoModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel;

class Carrito extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('logueado')) {
            return redirect()->to('/');
        }
        
        $idCliente = $session->get('idCliente');

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();

        // Buscar el carrito actual del cliente
        $pedido = $pedidoModel
            ->where('idCliente', $idCliente)
            ->where('estado', 'carrito')
            ->first();

        $carrito = [];
        $total = 0;

        if ($pedido) {

            // Obtener los detalles junto con los datos del producto
            $carrito = $detallePedidoModel
                ->select('detalle_pedido.*, item_pedido.nombre, item_pedido.urlImagen')
                ->join(
                    'item_pedido',
                    'item_pedido.idItem = detalle_pedido.idItem'
                )
                ->where('idPedido', $pedido['idPedido'])
                ->findAll();

            foreach ($carrito as &$item) {
                $item['precio'] = $item['precioUnitario'];
                $total += $item['subTotal'];
            }
        }

        return view('cliente/carrito', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }
        
    public function agregar()
    {
        $session = session();

        // Verificar si el cliente está logueado
        if (!$session->get('logueado')) {
            return $this->response->setJSON([
                'ok' => false,
                'login' => true,
                'mensaje' => 'Debes iniciar sesión para realizar un pedido.'
            ]);
        }

         // Obtener el cliente logueado
        $idCliente = $session->get('idCliente');

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

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();

        // Buscar si el cliente ya tiene un carrito
        $pedido = $pedidoModel
            ->where('idCliente', $idCliente)
            ->where('estado', 'carrito')
            ->first();

        // Si no tiene carrito, crear uno
        if (!$pedido) {

            $idPedido = $pedidoModel->insert([
                'idCliente' => $idCliente,
                'fecha' => date('Y-m-d H:i:s'),
                'estado' => 'carrito',
                'tipoEntrega' => null,
                'direccionEntrega' => null,
                'subTotal' => 0,
                'costoEnvio' => 0,
                'total' => 0
            ]);

            $pedido = $pedidoModel->find($idPedido);
        }
        
        // Verificar si el producto ya está en el carrito
        $detalle = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->where('idItem', $idItem)
            ->first();

        if ($detalle) {

            // Si ya existe, aumentar cantidad
            $nuevaCantidad = $detalle['cantidad'] + $cantidad;
            $nuevoSubtotal = $nuevaCantidad * $item['precio'];

            $detallePedidoModel->update($detalle['idDetallePedido'], [
                'cantidad' => $nuevaCantidad,
                'precioUnitario' => $item['precio'],
                'subTotal' => $nuevoSubtotal,
                'comentario' => $comentario !== ''
                    ? $comentario
                    : $detalle['comentario']
            ]);

        } else {

            // Agregar nuevo detalle
            $subtotal = $item['precio'] * $cantidad;

            $detallePedidoModel->insert([
                'idPedido' => $pedido['idPedido'],
                'idItem' => $item['idItem'],
                'cantidad' => $cantidad,
                'precioUnitario' => $item['precio'],
                'subTotal' => $subtotal,
                'comentario' => $comentario
            ]);
        }

        // Recalcular el subtotal del pedido
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        $subtotalPedido = 0;

        foreach ($detalles as $detalle) {
            $subtotalPedido += $detalle['subTotal'];
        }

        // Actualizar totales del pedido
        $pedidoModel->update($pedido['idPedido'], [
            'subTotal' => $subtotalPedido,
            'costoEnvio' => 0,
            'total' => $subtotalPedido
        ]);

        return $this->response->setJSON([
            'ok' => true,
            'mensaje' => 'Producto agregado al pedido.'
        ]);
    }

    public function aumentarCantidad()
    {
        $datos = $this->request->getJSON(true);
        $idItem = $datos['idItem'] ?? null;

        if (!$idItem) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se recibió el producto.'
            ]);
        }

        $session = session();
        $idCliente = $session->get('idCliente');

        if (!$idCliente) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Debes iniciar sesión.'
            ]);
        }

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Buscar el carrito actual del cliente
        $pedido = $pedidoModel
            ->where('idCliente', $idCliente)
            ->where('estado', 'carrito')
            ->first();

        if (!$pedido) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se encontró el carrito.'
            ]);
        }

        // Buscar el detalle del producto
        $detalle = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->where('idItem', $idItem)
            ->first();

        if (!$detalle) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El producto no está en el carrito.'
            ]);
        }

        // Obtener el precio del producto
        $item = $itemPedidoModel->find($idItem);

        if (!$item) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se encontró el producto.'
            ]);
        }

        // Aumentar cantidad
        $nuevaCantidad = $detalle['cantidad'] + 1;
        $nuevoSubtotal = $nuevaCantidad * $item['precio'];

        // Actualizar detalle
        $detallePedidoModel->update($detalle['idDetallePedido'], [
            'cantidad' => $nuevaCantidad,
            'precioUnitario' => $item['precio'],
            'subTotal' => $nuevoSubtotal
        ]);

        // Recalcular subtotal del pedido
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        $subtotalPedido = 0;

        foreach ($detalles as $detalle) {
            $subtotalPedido += $detalle['subTotal'];
        }

        // Actualizar pedido
        $pedidoModel->update($pedido['idPedido'], [
            'subTotal' => $subtotalPedido,
            'total' => $subtotalPedido
        ]);

        return $this->response->setJSON([
            'ok' => true
        ]);
    }

    public function disminuirCantidad()
    {
        $datos = $this->request->getJSON(true);
        $idItem = $datos['idItem'] ?? null;

        if (!$idItem) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se recibió el producto.'
            ]);
        }

        $session = session();
        $idCliente = $session->get('idCliente');

        if (!$idCliente) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Debes iniciar sesión.'
            ]);
        }

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Buscar el carrito actual
        $pedido = $pedidoModel
            ->where('idCliente', $idCliente)
            ->where('estado', 'carrito')
            ->first();

        if (!$pedido) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se encontró el carrito.'
            ]);
        }

        // Buscar el detalle
        $detalle = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->where('idItem', $idItem)
            ->first();

        if (!$detalle) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El producto no está en el carrito.'
            ]);
        }

        // Si ya es 1, no bajar más
        if ($detalle['cantidad'] <= 1) {
            return $this->response->setJSON([
                'ok' => true
            ]);
        }

        // Obtener precio
        $item = $itemPedidoModel->find($idItem);

        if (!$item) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se encontró el producto.'
            ]);
        }

        // Disminuir cantidad
        $nuevaCantidad = $detalle['cantidad'] - 1;
        $nuevoSubtotal = $nuevaCantidad * $item['precio'];

        // Actualizar detalle
        $detallePedidoModel->update($detalle['idDetallePedido'], [
            'cantidad' => $nuevaCantidad,
            'precioUnitario' => $item['precio'],
            'subTotal' => $nuevoSubtotal
        ]);

        // Recalcular subtotal
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        $subtotalPedido = 0;

        foreach ($detalles as $detalle) {
            $subtotalPedido += $detalle['subTotal'];
        }

        // Actualizar pedido
        $pedidoModel->update($pedido['idPedido'], [
            'subTotal' => $subtotalPedido,
            'total' => $subtotalPedido
        ]);

        return $this->response->setJSON([
            'ok' => true
        ]);
    }

    public function cantidadCarrito()
    {
        $session = session();

        if (!$session->get('logueado')) {
            return $this->response->setJSON([
                'cantidad' => 0
            ]);
        }

        $idCliente = $session->get('idCliente');

        $pedidoModel = new \App\Models\PedidoModel();
        $detalleModel = new \App\Models\DetallePedidoModel();

        $pedido = $pedidoModel
            ->where('idCliente', $idCliente)
            ->where('estado', 'carrito')
            ->first();

        if (!$pedido) {
            return $this->response->setJSON([
                'cantidad' => 0
            ]);
        }

        $resultado = $detalleModel
            ->selectSum('cantidad')
            ->where('idPedido', $pedido['idPedido'])
            ->first();

        return $this->response->setJSON([
            'cantidad' => (int) ($resultado['cantidad'] ?? 0)
        ]);
    }
}