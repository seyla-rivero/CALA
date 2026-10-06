<?php

namespace App\Controllers;

use App\Models\ZonaModel;

class Zona extends BaseController
{
    public function index()
    {
        $zonaModel = new ZonaModel();

        $idSucursal = session()->get('idSucursal');

        $datos['zonas'] = $zonaModel->obtenerZonasAdmin($idSucursal);

        return view('administrador/zonas', $datos);
    }

    public function agregarZona()
    {
        return view('administrador/agregar_zona');
    }

    public function guardarZona()
    {
        $rules = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'El nombre de la zona es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede superar los 100 caracteres.'
                ]
            ],

            'costoEnvio' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'El costo de envío es obligatorio.',
                    'numeric' => 'El costo de envío debe ser un número.',
                    'greater_than_equal_to' => 'El costo de envío no puede ser negativo.'
                ]
            ],
        ];

        // Validar
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $zonaModel = new ZonaModel();

        $idSucursal = session()->get('idSucursal');

        $zonaModel->insert([
            'nombre' => $this->request->getPost('nombre'),
            'costoEnvio' => $this->request->getPost('costoEnvio'),
            'activo' => 1,
            'idSucursal' => $idSucursal
        ]);

    return redirect()->to(base_url('admin/zonas'))
        ->with('mensajeExito', 'Zona agregada correctamente.');
    }

    public function editarZona(int $idZona)
    {
        $zonaModel = new ZonaModel();
        $idSucursal = session()->get('idSucursal');

        // Buscar la zona verificando que pertenezca a la sucursal del administrador
        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        $datos['zona'] = $zona;

        return view('administrador/editar_zona', $datos);
    }

    public function actualizarZona(int $idZona)
    {
        $rules = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'El nombre de la zona es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede superar los 100 caracteres.'
                ]
            ],

            'costoEnvio' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El costo de envío es obligatorio.',
                    'numeric' => 'El costo de envío debe ser un número.',
                    'greater_than_equal_to' => 'El costo de envío no puede ser negativo.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $zonaModel = new ZonaModel();

        $idSucursal = session()->get('idSucursal');

        // Verificar nuevamente que la zona pertenezca a la sucursal
        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'costoEnvio' => $this->request->getPost('costoEnvio')
        ];

        $zonaModel->update($idZona, $datos);

        return redirect()->to(base_url('admin/zonas'))
            ->with('mensajeExito', 'Zona actualizada correctamente.');
    }

    public function desactivarZona(int $idZona)
    {
        $zonaModel = new ZonaModel();

        $idSucursal = session()->get('idSucursal');

        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        $zonaModel->update($idZona, [
            'activo' => 0
        ]);

        return redirect()->to(base_url('admin/zonas'));
    }
    public function activarZona(int $idZona)
    {
        $zonaModel = new ZonaModel();

        $idSucursal = session()->get('idSucursal');

        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        $zonaModel->update($idZona, [
            'activo' => 1
        ]);

        return redirect()->to(base_url('admin/zonas'));
    }
}    