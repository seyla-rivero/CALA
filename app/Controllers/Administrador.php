<?php

namespace App\Controllers;

use App\Models\AdministradorModel;
use App\Models\PedidoModel;

class Administrador extends BaseController
{
    public function login()
    {
        return view('administrador/login');
    }

    public function validarLogin()
    {
        $rules = [
            'email' => [
                'rules' => 'required|regex_match[/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u]|is_not_unique[administrador.email]',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'regex_match' => 'Ingresá un email válido',
                    'is_not_unique' => 'El email no está registrado'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria',
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('erroresLoginAdmin', $this->validator->getErrors());
        }

        $administradorModel = new AdministradorModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $administrador = $administradorModel
            ->where('email', $email)
            ->first(); 

        if (
            !$administrador ||
            !password_verify($password, $administrador['contraseña'])
        ) {
            return redirect()->back()
                ->withInput()
                ->with('erroresLoginAdmin', [
                    'password' => 'El correo o la contraseña son incorrectos.'
                ]);
        }

        session()->set([
            'idAdministrador' => $administrador['idAdministrador'],
            'nombreAdministrador' => $administrador['nombre'],
            'idSucursal' => $administrador['idSucursal'],
            'administradorLogueado' => true
        ]);
        // Cerrar la autenticación del cliente
        session()->remove([
            'idCliente',
            'nombre',
            'logueado'
        ]);

        return redirect()->to('/admin/panel');
    }

    public function panel()
    {
        // Verificar que haya un administrador autenticado
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        return view('administrador/panel');
    }
    public function logoutAdmi()
    {
        session()->remove([
            'idAdministrador',
            'nombreAdministrador',
            'idSucursal',
            'administradorLogueado'
        ]);

        return redirect()->to('/admin');
    }
    
    public function pedidos()
    {
        // Verificar que haya un administrador autenticado.
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        $idSucursal = session()->get('idSucursal');

        if (!$idSucursal) {
            return redirect()->to('/admin');
        }

        $db = \Config\Database::connect();

        // Obtener los pedidos de la sucursal.
        $pedidos = $db->table('pedido p')
            ->select('
                p.*,
                c.nombre AS nombreCliente,
                c.apellido AS apellidoCliente,
                c.email AS emailCliente,
                c.telefono AS telefonoCliente,
                z.nombre AS nombreZona
            ')
            ->join('cliente c', 'c.idCliente = p.idCliente')
            ->join('zona z', 'z.idZona = p.idZona', 'left')
            ->where('p.idSucursal', $idSucursal)
            ->orderBy('p.fecha', 'DESC')
            ->get()
            ->getResultArray();

        // Obtener los detalles de todos los pedidos.
        $detallesPorPedido = [];

        if (!empty($pedidos)) {
            $idsPedidos = array_column($pedidos, 'idPedido');

            $detalles = $db->table('detalle_pedido d')
                ->select('
                    d.*,
                    i.nombre AS nombreProducto,
                    i.descripcion AS descripcionProducto,
                    i.urlImagen AS imagenProducto
                ')
                ->join('item_pedido i', 'i.idItem = d.idItem')
                ->whereIn('d.idPedido', $idsPedidos)
                ->get()
                ->getResultArray();

            foreach ($detalles as $detalle) {
                $detallesPorPedido[$detalle['idPedido']][] = $detalle;
            }
        }

        // Preparar los datos para la vista.
        foreach ($pedidos as &$pedido) {
            $pedido['detalles'] =
                $detallesPorPedido[$pedido['idPedido']] ?? [];

            // Estado del pedido.
            $estado = strtolower(trim((string) ($pedido['estado'] ?? '')));

            $claseEstado = 'otro';

            if (str_contains($estado, 'pendiente')) {
                $claseEstado = 'pendiente';
            } elseif (str_contains($estado, 'prepar')) {
                $claseEstado = 'preparacion';
            } elseif (
                str_contains($estado, 'camino') ||
                str_contains($estado, 'reparto')
            ) {
                $claseEstado = 'camino';
            } elseif (
                str_contains($estado, 'entregado') ||
                str_contains($estado, 'retirado') ||
                str_contains($estado, 'finalizado')
            ) {
                $claseEstado = 'finalizado';
            } elseif (str_contains($estado, 'cancel')) {
                $claseEstado = 'cancelado';
            }

            $pedido['claseEstado'] = $claseEstado;

            // Datos del cliente.
            $pedido['nombreCompleto'] = trim(
                ($pedido['nombreCliente'] ?? '') . ' ' .
                ($pedido['apellidoCliente'] ?? '')
            ) ?: 'Cliente';

            // Tipo de entrega.
            $tipoEntrega = strtolower(
                trim((string) ($pedido['tipoEntrega'] ?? ''))
            );

            $pedido['esDelivery'] = str_contains($tipoEntrega, 'delivery');
            $pedido['claseEntrega'] = $pedido['esDelivery']
                ? 'delivery'
                : 'retiro';

            // Método de pago.
            $metodoPago = strtolower(
                trim((string) ($pedido['metodoPago'] ?? ''))
            );

            $claseMetodoPago = 'otro';

            if (str_contains($metodoPago, 'efectivo')) {
                $claseMetodoPago = 'efectivo';
            } elseif (str_contains($metodoPago, 'transferencia')) {
                $claseMetodoPago = 'transferencia';
            }

            $pedido['claseMetodoPago'] = $claseMetodoPago;

            // Estado del pago.
            $estadoPago = strtolower(
                trim((string) ($pedido['estadoPago'] ?? ''))
            );

            $clasePago = '';
            $textoEstadoPago = '';

            if (str_contains($estadoPago, 'rechaz')) {
                $clasePago = 'rechazado';
                $textoEstadoPago = 'Rechazado';
            } elseif (str_contains($estadoPago, 'verific')) {
                $clasePago = 'verificado';
                $textoEstadoPago = 'Verificado';
            } elseif (
                str_contains($estadoPago, 'aprob') ||
                str_contains($estadoPago, 'pagado') ||
                str_contains($estadoPago, 'confirmado')
            ) {
                $clasePago = 'aprobado';
                $textoEstadoPago = 'Pagado';
            } elseif (str_contains($metodoPago, 'efectivo')) {
                $textoEstadoPago = 'A cobrar';
            } elseif (str_contains($metodoPago, 'transferencia')) {
                $clasePago = 'pendiente';
                $textoEstadoPago = 'Por verificar';
            } else {
                $textoEstadoPago = 'Pendiente';
            }

            $pedido['clasePago'] = $clasePago;
            $pedido['textoEstadoPago'] = $textoEstadoPago;

            // Fecha del pedido.
            $fechaMostrar = 'Fecha no disponible';

            if (!empty($pedido['fecha'])) {
                $timestamp = strtotime($pedido['fecha']);

                if ($timestamp !== false) {
                    $fechaMostrar = date('d/m/Y · H:i', $timestamp);
                }
            }

            $pedido['fechaMostrar'] = $fechaMostrar;

            // Acciones permitidas para el estado actual.
            $accionesPedido = [
                'pendiente' => [
                    'estado' => 'en preparación',
                    'texto' => 'Pasar a preparación',
                    'icono' => 'bi-fire',
                    'clase' => 'btn-cala-preparar',
                ],
                'en preparación' => [
                    'estado' => 'listo',
                    'texto' => 'Marcar como listo',
                    'icono' => 'bi-check-circle-fill',
                    'clase' => 'btn-cala-listo',
                ],
            ];

            $pedido['accion'] = $accionesPedido[$estado] ?? null;

            if ($estado === 'listo') {
                $esDelivery = $pedido['esDelivery'];

                $pedido['accion'] = [
                    'estado' => $esDelivery ? 'entregado' : 'retirado',
                    'texto' => $esDelivery
                        ? 'Marcar como entregado'
                        : 'Marcar como retirado',
                    'icono' => 'bi-check2-circle',
                    'clase' => 'btn-cala-finalizar',
                ];
            }
        }

        unset($pedido);

        return view('administrador/pedidos', [
            'pedidos' => $pedidos,
        ]);
    }  
    
    public function actualizarEstadoPedido(int $idPedido)
    {
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        $idSucursal = session()->get('idSucursal');

        if (!$idSucursal) {
            return redirect()->to('/admin');
        }

        $nuevoEstado = strtolower(trim(
            (string) $this->request->getPost('nuevoEstado')
        ));

        $pedidoModel = new PedidoModel();

        $pedido = $pedidoModel
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$pedido) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se encontró el pedido.');
        }

        $estadoActual = strtolower(trim(
            (string) ($pedido['estado'] ?? '')
        ));

        // Transiciones permitidas.
        $transiciones = [
            'pendiente' => ['en preparación'],
            'en preparación' => ['listo'],
            'listo' => [
                'entregado',
                'retirado'
            ],
        ];

        if (
            !isset($transiciones[$estadoActual]) ||
            !in_array(
                $nuevoEstado,
                $transiciones[$estadoActual],
                true
            )
        ) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se puede realizar ese cambio de estado.');
        }

        // Determinar la modalidad de entrega.
        if ($nuevoEstado === 'entregado' || $nuevoEstado === 'retirado') {
            $tipoEntrega = strtolower(trim(
                (string) ($pedido['tipoEntrega'] ?? '')
            ));

            if (
                ($nuevoEstado === 'entregado' &&
                    !str_contains($tipoEntrega, 'delivery')) ||
                ($nuevoEstado === 'retirado' &&
                    !str_contains($tipoEntrega, 'retiro'))
            ) {
                return redirect()->to(base_url('admin/pedidos'))
                    ->with('error', 'El estado no coincide con el tipo de entrega.');
            }
        }

        $db = \Config\Database::connect();

        $actualizado = $db->table('pedido')
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->where('estado', $pedido['estado'])
            ->update([
                'estado' => $nuevoEstado
            ]);

        if (!$actualizado || $db->affectedRows() !== 1) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se pudo actualizar el pedido. Revisá su estado actual.');
        }

        $mensajes = [
            'en preparación' => 'El pedido pasó a preparación.',
            'listo' => 'El pedido está listo.',
            'entregado' => 'El pedido fue marcado como entregado.',
            'retirado' => 'El pedido fue marcado como retirado.',
        ];

        return redirect()->to(base_url('admin/pedidos'))
            ->with('mensaje', $mensajes[$nuevoEstado]);
    }
    
    public function verificarPagoTransferencia(int $idPedido)
    {
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        $idSucursal = session()->get('idSucursal');

        if (!$idSucursal) {
            return redirect()->to('/admin');
        }

        $pedidoModel = new PedidoModel();

        $pedido = $pedidoModel
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$pedido) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se encontró el pedido.');
        }

        $metodoPago = strtolower(trim(
            (string) ($pedido['metodoPago'] ?? '')
        ));

        $estadoPago = strtolower(trim(
            (string) ($pedido['estadoPago'] ?? '')
        ));

        if (!str_contains($metodoPago, 'transferencia')) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'Este pedido no utiliza transferencia.');
        }

        if (
            str_contains($estadoPago, 'rechaz') ||
            str_contains($estadoPago, 'verific')
        ) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'El pago ya fue verificado o rechazado.');
        }

        $db = \Config\Database::connect();

        $actualizado = $db->table('pedido')
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->update([
                'estadoPago' => 'verificado'
            ]);

        if (!$actualizado || $db->affectedRows() !== 1) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se pudo verificar el pago.');
        }

        return redirect()->to(base_url('admin/pedidos'))
            ->with('mensaje', 'La transferencia fue verificada correctamente.');
    }

}