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
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El costo de envío es obligatorio.',
                    'numeric' => 'El costo de envío debe ser un número.',
                    'greater_than_equal_to' => 'El costo de envío no puede ser negativo.'
                ]
            ],
        ];

        // Validar los datos recibidos.
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Verificar que el administrador tenga una sucursal asociada.
        $idSucursal = session()->get('idSucursal');

        if (empty($idSucursal)) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'No se pudo identificar la sucursal del administrador.');
        }

        $zonaModel = new ZonaModel();

        try {
            $resultado = $zonaModel->insert([
                'nombre' => trim($this->request->getPost('nombre')),
                'costoEnvio' => $this->request->getPost('costoEnvio'),
                'activo' => 1,
                'idSucursal' => $idSucursal
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo guardar la zona para la sucursal ID ' . $idSucursal);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo guardar la zona. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al guardar la zona: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la zona.');
        }

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
        $zonaModel = new ZonaModel();
        $idSucursal = session()->get('idSucursal');

        // Verificar que la zona pertenezca a la sucursal.
        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

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
            ]
        ];

        // Validar los datos recibidos.
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'nombre' => trim($this->request->getPost('nombre')),
            'costoEnvio' => $this->request->getPost('costoEnvio')
        ];

        try {
            $resultado = $zonaModel->update($idZona, $datos);

            if (!$resultado) {
                log_message('error', 'No se pudo actualizar la zona ID ' . $idZona);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo actualizar la zona. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al actualizar la zona ID ' . $idZona . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la zona.');
        }

        return redirect()->to(base_url('admin/zonas'))
            ->with('mensajeExito', 'Zona actualizada correctamente.');
    }

    public function desactivarZona(int $idZona)
    {
        $zonaModel = new ZonaModel();
        $idSucursal = session()->get('idSucursal');

        // Verificar que la zona pertenezca a la sucursal.
        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        try {
            $resultado = $zonaModel->update($idZona, [
                'activo' => 0
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo desactivar la zona ID ' . $idZona);

                return redirect()->to(base_url('admin/zonas'))
                    ->with('error', 'No se pudo desactivar la zona. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al desactivar la zona ID ' . $idZona . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'Ocurrió un error al desactivar la zona.');
        }

        return redirect()->to(base_url('admin/zonas'))
            ->with('mensajeExito', 'Zona desactivada correctamente.');
    }
    public function activarZona(int $idZona)
    {
        $zonaModel = new ZonaModel();
        $idSucursal = session()->get('idSucursal');

        // Verificar que la zona pertenezca a la sucursal.
        $zona = $zonaModel
            ->where('idZona', $idZona)
            ->where('idSucursal', $idSucursal)
            ->first();

        if (!$zona) {
            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'La zona no existe o no pertenece a tu sucursal.');
        }

        try {
            $resultado = $zonaModel->update($idZona, [
                'activo' => 1
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo activar la zona ID ' . $idZona);

                return redirect()->to(base_url('admin/zonas'))
                    ->with('error', 'No se pudo activar la zona. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al activar la zona ID ' . $idZona . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/zonas'))
                ->with('error', 'Ocurrió un error al activar la zona.');
        }

        return redirect()->to(base_url('admin/zonas'))
            ->with('mensajeExito', 'Zona activada correctamente.');
    }
}    