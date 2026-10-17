<?php

namespace App\Controllers;

use App\Models\ItemPedidoModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel;
use App\Models\ZonaModel;
use App\Models\SucursalModel;

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

            // Obtener los detalles del carrito junto con los datos actuales
            // de cada producto
            $carrito = $detallePedidoModel
                ->select('detalle_pedido.*, item_pedido.nombre, item_pedido.urlImagen, item_pedido.precio AS precioActual')
                ->join(
                    'item_pedido',
                    'item_pedido.idItem = detalle_pedido.idItem'
                )
                ->where('idPedido', $pedido['idPedido'])
                ->findAll();

            foreach ($carrito as &$item) {

                // Obtener el precio actual del producto
                $precioActual = $item['precioActual'];
                $cantidad = (int) $item['cantidad'];

                // Recalcular el subtotal con el precio vigente
                $subtotal = $precioActual * $cantidad;

                // Actualizar el detalle del pedido en la base de datos
                $detallePedidoModel->update(
                    $item['idDetallePedido'],
                    [
                        'precioUnitario' => $precioActual,
                        'subTotal' => $subtotal
                    ]
                );

                // Actualizar los datos que se muestran en la vista
                $item['precio'] = $precioActual;
                $item['precioUnitario'] = $precioActual;
                $item['subTotal'] = $subtotal;

                // Acumular el total del pedido
                $total += $subtotal;
            }

            unset($item);

            // Actualizar el subtotal y el total del pedido
            $pedidoModel->update(
                $pedido['idPedido'],
                [
                    'subTotal' => $total,
                    'total' => $total
                ]
            );
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
        $bebida = trim($datos['bebida'] ?? '');
        $empanadas = trim($datos['empanadas'] ?? '');

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
        
        // Buscar detalles del mismo producto dentro del carrito
        $detallesExistentes = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->where('idItem', $idItem)
            ->findAll();

        // Buscar un detalle que tenga las mismas opciones y comentario
        $detalle = null;

        foreach ($detallesExistentes as $detalleExistente) {

            $mismoComentario = trim(
                (string) ($detalleExistente['comentario'] ?? '')
            ) === $comentario;

            $mismaBebida = trim(
                (string) ($detalleExistente['bebida'] ?? '')
            ) === $bebida;

            $mismasEmpanadas = trim(
                (string) ($detalleExistente['empanadas'] ?? '')
            ) === $empanadas;

            if (
                $mismoComentario &&
                $mismaBebida &&
                $mismasEmpanadas
            ) {
                $detalle = $detalleExistente;
                break;
            }
        }

        if ($detalle) {

            // Si coincide el producto y sus opciones, sumar cantidad
            $nuevaCantidad = (int) $detalle['cantidad'] + (int) $cantidad;
            $nuevoSubtotal = $nuevaCantidad * $item['precio'];

            $detallePedidoModel->update(
                $detalle['idDetallePedido'],
                [
                    'cantidad' => $nuevaCantidad,
                    'precioUnitario' => $item['precio'],
                    'subTotal' => $nuevoSubtotal
                ]
            );

        } else {

            // Si las opciones o el comentario son diferentes,
            // crear un nuevo detalle en el carrito
            $subtotal = $item['precio'] * (int) $cantidad;

            $detallePedidoModel->insert([
                'idPedido' => $pedido['idPedido'],
                'idItem' => $item['idItem'],
                'cantidad' => (int) $cantidad,
                'precioUnitario' => $item['precio'],
                'subTotal' => $subtotal,
                'comentario' => $comentario,
                'bebida' => $bebida !== '' ? $bebida : null,
                'empanadas' => $empanadas !== '' ? $empanadas : null
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
        $idDetallePedido = $datos['idDetallePedido'] ?? null;

        if (!$idDetallePedido) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se recibió el detalle del pedido.'
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

        // Buscar la línea específica dentro del carrito del cliente
        $detalle = $detallePedidoModel
            ->where('idDetallePedido', $idDetallePedido)
            ->where('idPedido', $pedido['idPedido'])
            ->first();

        if (!$detalle) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El artículo no está en el carrito.'
            ]);
        }

        // Verificar que el producto siga disponible
        $item = $itemPedidoModel
            ->where('idItem', $detalle['idItem'])
            ->where('activo', 1)
            ->first();

        if (!$item) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El producto ya no está disponible.'
            ]);
        }

        // Aumentar únicamente la cantidad de esta línea
        $nuevaCantidad = (int) $detalle['cantidad'] + 1;
        $nuevoSubtotal = $nuevaCantidad * (float) $item['precio'];

        $detallePedidoModel->update(
            $detalle['idDetallePedido'],
            [
                'cantidad' => $nuevaCantidad,
                'precioUnitario' => $item['precio'],
                'subTotal' => $nuevoSubtotal
            ]
        );

        // Recalcular el subtotal del pedido
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        $subtotalPedido = 0;

        foreach ($detalles as $detalleActual) {
            $subtotalPedido += $detalleActual['subTotal'];
        }

        // Actualizar los totales del carrito
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
        $idDetallePedido = $datos['idDetallePedido'] ?? null;

        if (!$idDetallePedido) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'No se recibió el detalle del pedido.'
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

        // Buscar la línea específica dentro del carrito
        $detalle = $detallePedidoModel
            ->where('idDetallePedido', $idDetallePedido)
            ->where('idPedido', $pedido['idPedido'])
            ->first();

        if (!$detalle) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El artículo no está en el carrito.'
            ]);
        }

        // Si la cantidad es 1, eliminar solamente esta línea
        if ((int) $detalle['cantidad'] <= 1) {

            $detallePedidoModel->delete($detalle['idDetallePedido']);

        } else {

            // Obtener el precio actual del producto
            $item = $itemPedidoModel->find($detalle['idItem']);

            if (!$item) {
                return $this->response->setJSON([
                    'ok' => false,
                    'mensaje' => 'No se encontró el producto.'
                ]);
            }

            // Disminuir la cantidad de esta línea
            $nuevaCantidad = (int) $detalle['cantidad'] - 1;
            $nuevoSubtotal = $nuevaCantidad * (float) $item['precio'];

            $detallePedidoModel->update(
                $detalle['idDetallePedido'],
                [
                    'cantidad' => $nuevaCantidad,
                    'precioUnitario' => $item['precio'],
                    'subTotal' => $nuevoSubtotal
                ]
            );
        }

        // Recalcular el subtotal del pedido
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        $subtotalPedido = 0;

        foreach ($detalles as $detalleActual) {
            $subtotalPedido += $detalleActual['subTotal'];
        }

        // Actualizar los totales del carrito
        $pedidoModel->update($pedido['idPedido'], [
            'subTotal' => $subtotalPedido,
            'total' => $subtotalPedido
        ]);

        return $this->response->setJSON([
            'ok' => true
        ]);
    }
    
    // Contador de cuantas cosas hay en el carrito
    public function cantidadCarrito()
    {
        $session = session();

        if (!$session->get('logueado')) {
            return $this->response->setJSON([
                'cantidad' => 0
            ]);
        }

        $idCliente = $session->get('idCliente');

        $pedidoModel = new PedidoModel();
        $detalleModel = new DetallePedidoModel();

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
    
    public function checkout()
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

        // Si no existe un carrito, volver al carrito
        if (!$pedido) {
            return redirect()->to('/carrito');
        }

        // Obtener los productos del carrito con sus precios actuales
        $carrito = $detallePedidoModel
            ->select('detalle_pedido.*, item_pedido.nombre, item_pedido.urlImagen, item_pedido.precio AS precioActual')
            ->join(
                'item_pedido',
                'item_pedido.idItem = detalle_pedido.idItem'
            )
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        // Si el carrito está vacío
        if (empty($carrito)) {
            return redirect()->to('/carrito');
        }

        $total = 0;

        foreach ($carrito as &$item) {

            // Tomar el precio vigente del producto
            $precioActual = (float) $item['precioActual'];
            $cantidad = (int) $item['cantidad'];

            // Recalcular el subtotal
            $subtotal = $precioActual * $cantidad;

            // Guardar el precio y subtotal actualizados
            $detallePedidoModel->update(
                $item['idDetallePedido'],
                [
                    'precioUnitario' => $precioActual,
                    'subTotal' => $subtotal
                ]
            );

            // Actualizar los datos que recibe la vista
            $item['precio'] = $precioActual;
            $item['precioUnitario'] = $precioActual;
            $item['subTotal'] = $subtotal;

            $total += $subtotal;
        }

        unset($item);

        // Actualizar los importes del carrito
        $pedidoModel->update($pedido['idPedido'], [
            'subTotal' => $total,
            'total' => $total
        ]);

        // Actualizar los datos del pedido utilizados en la vista
        $pedido['subTotal'] = $total;
        $pedido['total'] = $total;

        $zonaModel = new ZonaModel();
        $sucursalModel = new SucursalModel();

        $zonas = $zonaModel
            ->where('activo', 1)
            ->findAll();

        $sucursales = $sucursalModel
            ->where('activo', 1)
            ->findAll();

        return view('cliente/checkout', [
            'pedido' => $pedido,
            'carrito' => $carrito,
            'total' => $total,
            'zonas' => $zonas,
            'sucursales' => $sucursales
        ]);
    }

    public function confirmarPedido()
    {
        $session = session();

        // Verificar que el cliente esté logueado
        if (!$session->get('logueado')) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Debes iniciar sesión.'
            ]);
        }

        $idCliente = $session->get('idCliente');

        // Obtener los datos enviados desde JavaScript
        $datos = $this->request->getJSON(true);

        $tipoEntrega = $datos['tipoEntrega'] ?? null;
        $idSucursal = $datos['idSucursal'] ?? null;
        $idZona = $datos['idZona'] ?? null;
        $direccionEntrega = trim($datos['direccionEntrega'] ?? '');
        $metodoPago = $datos['metodoPago'] ?? null;

        // Validar tipo de entrega
        if (!in_array($tipoEntrega, ['retiro', 'delivery'])) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Seleccioná una modalidad de entrega.'
            ]);
        }

        // Validar sucursal
        if (!$idSucursal) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Seleccioná una sucursal.'
            ]);
        }

        // Si es delivery, validar zona y dirección
        if ($tipoEntrega === 'delivery') {

            if (!$idZona) {
                return $this->response->setJSON([
                    'ok' => false,
                    'mensaje' => 'Seleccioná una zona de cobertura.'
                ]);
            }

            if ($direccionEntrega === '') {
                return $this->response->setJSON([
                    'ok' => false,
                    'mensaje' => 'Ingresá tu dirección de entrega.'
                ]);
            }
        } else {
            // Si es retiro, no hay dirección ni zona
            $idZona = null;
            $direccionEntrega = null;
        }

        // Validar método de pago
        if (!in_array($metodoPago, ['efectivo', 'transferencia'])) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'Seleccioná un método de pago.'
            ]);
        }

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();
        $zonaModel = new ZonaModel();
        $sucursalModel = new SucursalModel();

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

        // Verificar que tenga productos
        $detalles = $detallePedidoModel
            ->where('idPedido', $pedido['idPedido'])
            ->findAll();

        if (empty($detalles)) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'El carrito está vacío.'
            ]);
        }

        // Verificar que exista la sucursal
        $sucursal = $sucursalModel
            ->where('idSucursal', $idSucursal)
            ->where('activo', 1)
            ->first();

        if (!$sucursal) {
            return $this->response->setJSON([
                'ok' => false,
                'mensaje' => 'La sucursal seleccionada no está disponible.'
            ]);
        }

        // Actualizar los precios antes de confirmar el pedido
        $itemPedidoModel = new ItemPedidoModel();
        $subtotal = 0;

        foreach ($detalles as $detalle) {

            // Consultar el precio vigente del producto
            $item = $itemPedidoModel
                ->where('idItem', $detalle['idItem'])
                ->where('activo', 1)
                ->first();

            // No confirmar si el producto ya no está disponible
            if (!$item) {
                return $this->response->setJSON([
                    'ok' => false,
                    'mensaje' => 'Uno de los productos del carrito ya no está disponible. Volvé al carrito para revisar tu pedido.'
                ]);
            }

            $precioActual = (float) $item['precio'];
            $cantidad = (int) $detalle['cantidad'];
            $nuevoSubtotal = $precioActual * $cantidad;

            // Actualizar el detalle del pedido
            $detallePedidoModel->update(
                $detalle['idDetallePedido'],
                [
                    'precioUnitario' => $precioActual,
                    'subTotal' => $nuevoSubtotal
                ]
            );

            $subtotal += $nuevoSubtotal;
        }

        // Calcular costo de envío
        $costoEnvio = 0;

        if ($tipoEntrega === 'delivery') {

            $zona = $zonaModel
                ->where('idZona', $idZona)
                ->where('idSucursal', $idSucursal)
                ->where('activo', 1)
                ->first();

            if (!$zona) {
                return $this->response->setJSON([
                    'ok' => false,
                    'mensaje' => 'La zona seleccionada no pertenece a la sucursal indicada.'
                ]);
            }

            $costoEnvio = $zona['costoEnvio'];
        }

        // Calcular total
        $total = $subtotal + $costoEnvio;

        // Determinar estado del pago
        if ($metodoPago === 'transferencia') {
            $estadoPago = 'pendiente';
        } else {
            $estadoPago = 'no_aplica';
        }

        // Actualizar el pedido
        $pedidoModel->update($pedido['idPedido'], [
            'idSucursal' => $idSucursal,
            'idZona' => $idZona,
            'fecha' => date('Y-m-d H:i:s'),
            'estado' => 'pendiente',
            'tipoEntrega' => $tipoEntrega,
            'direccionEntrega' => $direccionEntrega,
            'metodoPago' => $metodoPago,
            'estadoPago' => $estadoPago,
            'subTotal' => $subtotal,
            'costoEnvio' => $costoEnvio,
            'total' => $total
        ]);

        return $this->response->setJSON([
            'ok' => true,
            'mensaje' => 'Pedido confirmado correctamente.',
            'idPedido' => $pedido['idPedido']
        ]);
    }
    
    
    public function misPedidos()
    {
        $session = session();

        // Verificar que el cliente haya iniciado sesión
        if (!$session->get('logueado')) {
            return redirect()->to('/');
        }

        $idCliente = $session->get('idCliente');

        $pedidoModel = new PedidoModel();
        $detallePedidoModel = new DetallePedidoModel();

        // Obtener los pedidos del cliente, excepto el carrito actual
        $pedidos = $pedidoModel
            ->select('pedido.*, sucursal.nombre AS nombreSucursal,
                    zona.nombre AS nombreZona')
            ->join(
                'sucursal',
                'sucursal.idSucursal = pedido.idSucursal',
                'left'
            )
            ->join(
                'zona',
                'zona.idZona = pedido.idZona',
                'left'
            )
            ->where('pedido.idCliente', $idCliente)
            ->where('pedido.estado !=', 'carrito')
            ->orderBy('pedido.fecha', 'DESC')
            ->findAll();

        // Obtener los productos de cada pedido
        foreach ($pedidos as &$pedido) {

            $pedido['detalles'] = $detallePedidoModel
                ->select(
                    'detalle_pedido.*,
                    item_pedido.nombre,
                    item_pedido.urlImagen'
                )
                ->join(
                    'item_pedido',
                    'item_pedido.idItem = detalle_pedido.idItem',
                    'left'
                )
                ->where('detalle_pedido.idPedido', $pedido['idPedido'])
                ->findAll();
        }

        unset($pedido);

        return view('cliente/mis_pedidos', [
            'pedidos' => $pedidos
        ]);
    }

}