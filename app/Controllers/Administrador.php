<?php

namespace App\Controllers;

use App\Models\AdministradorModel;

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
        // Verificar que haya un administrador autenticado
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        $idSucursal = session()->get('idSucursal');

        if (!$idSucursal) {
            return redirect()->to('/admin');
        }

        $db = \Config\Database::connect();

        // Obtener pedidos de la sucursal del administrador
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

        // Obtener los detalles de los pedidos
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

            // Agrupar productos por pedido
            $detallesPorPedido = [];

            foreach ($detalles as $detalle) {
                $detallesPorPedido[$detalle['idPedido']][] = $detalle;
            }

            foreach ($pedidos as &$pedido) {
                $pedido['detalles'] =
                    $detallesPorPedido[$pedido['idPedido']] ?? [];
            }

            unset($pedido);

        }

        return view('administrador/pedidos', [
            'pedidos' => $pedidos
        ]);
    }
    
    public function actualizarEstadoPedido($idPedido)
    {
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        $idSucursal = session()->get('idSucursal');

        if (!$idSucursal) {
            return redirect()->to('/admin');
        }

        $pedidoModel = new \App\Models\PedidoModel();

        // Verificar que el pedido pertenezca a esta sucursal
        $pedido = $pedidoModel
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$pedido) {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'No se encontró el pedido.');
        }

        // Solo permitir pasar pedidos pendientes a preparación
        if ($pedido['estado'] !== 'pendiente') {
            return redirect()->to(base_url('admin/pedidos'))
                ->with('error', 'El estado del pedido ya fue modificado.');
        }
               
        $db = \Config\Database::connect();

        $datosActualizar = [
            'estado' => 'en preparación'
        ];

        // Si el pedido es por transferencia, verificar el pago
        $metodoPago = strtolower(trim(
            (string) ($pedido['metodoPago'] ?? '')
        ));

        if (str_contains($metodoPago, 'transferencia')) {
            $datosActualizar['estadoPago'] = 'verificado';
        }

        // Actualizar el pedido
        $db->table('pedido')
            ->where('idPedido', $idPedido)
            ->where('idSucursal', $idSucursal)
            ->update($datosActualizar);

        return redirect()->to(base_url('admin/pedidos'))
            ->with('mensaje', 'El pedido pasó a preparación correctamente.');
    }

}