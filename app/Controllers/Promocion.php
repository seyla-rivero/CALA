<?php

namespace App\Controllers;

use App\Models\PromocionModel;
use App\Models\ItemPedidoModel;

class Promocion extends BaseController
{
    public function promociones()
    {
        $promocionesModel = new PromocionModel();

        $datos['promociones'] = $promocionesModel->obtenerTodasLasPromociones();

        $datos['promocionesActivas'] = $promocionesModel->obtenerPromocionesActivas();

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

        $reglas = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'El nombre de la promoción es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede superar los 100 caracteres.'
                ]
            ],

            'descripcion' => [
                'rules' => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => 'La descripción no puede superar los 500 caracteres.'
                ]
            ],

            'precio' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'El precio es obligatorio.',
                    'numeric' => 'El precio debe ser un número.',
                    'greater_than' => 'El precio debe ser mayor a 0.'
                ]
            ],

            'imagen' => [
                'rules' => 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]|max_size[imagen,3072]',
                'errors' => [
                    'uploaded' => 'Debés seleccionar una imagen.',
                    'is_image' => 'El archivo seleccionado no es una imagen válida.',
                    'mime_in' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
                    'max_size' => 'La imagen no puede superar los 3 MB.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Obtener los datos del formulario.
        $incluyeBebida = $this->request->getPost('incluyeBebida') ? 1 : 0;
        $incluyeEmpanadas = $this->request->getPost('incluyeEmpanadas') ? 1 : 0;

        // Obtener y guardar la imagen.
        $imagen = $this->request->getFile('imagen');
        $nombreImagen = $imagen->getRandomName();
        $rutaImagen = FCPATH . 'img/' . $nombreImagen;

        try {
            $imagen->move(FCPATH . 'img', $nombreImagen);
        } catch (\Throwable $e) {
            log_message('error', 'Error al guardar imagen de promoción: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar la imagen. Intentá nuevamente.');
        }

        // Datos generales de la promoción.
        $datosItem = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'urlImagen' => $nombreImagen,
            'precio' => $this->request->getPost('precio'),
            'activo' => 1
        ];

        // Iniciar la transacción.
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
        // Guardar los datos generales en item_pedido.
            if ($itemPedidoModel->insert($datosItem) === false) {
                throw new \RuntimeException('No se pudieron guardar los datos generales de la promoción.');
            }

            $idItem = $itemPedidoModel->getInsertID();

            if (!$idItem) {
                throw new \RuntimeException('No se pudo obtener el identificador de la promoción.');
            }

            // Registrar los datos específicos en promocion.
            if ($promocionModel->insert([
                'idItem' => $idItem,
                'esPromoDia' => 0,
                'esMasVendida' => 0,
                'incluyeBebida' => $incluyeBebida,
                'incluyeEmpanadas' => $incluyeEmpanadas
            ]) === false) {
                throw new \RuntimeException('No se pudo registrar la promoción.');
            }

            // Comprobar el estado de la transacción.
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Falló la transacción al guardar la promoción.');
            }

            // Confirmar ambas inserciones.
            if (!$db->transCommit()) {
                throw new \RuntimeException('No se pudo confirmar la transacción.');
            }

        } catch (\Throwable $e) {
            $db->transRollback();

            // Eliminar la imagen si falló el guardado.
            if (is_file($rutaImagen)) {
                @unlink($rutaImagen);
            }

            log_message('error', 'Error al guardar promoción: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar la promoción. Intentá nuevamente.');
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'La promoción se agregó correctamente.');
    }

    public function guardarMasVendida()
    {
        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();

        $idItem = $this->request->getPost('masVendida');
        // Verificar que la promoción seleccionada exista y esté activa.
        if (!empty($idItem)) {
            $promocion = $promocionModel->find($idItem);
            $item = $itemPedidoModel->find($idItem);

            if (!$promocion || !$item || $item['activo'] != 1) {
                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'La promoción seleccionada no existe o está desactivada.');
            }
        }

         // Quitar la marca de más vendida de todas las promociones.
        $promocionModel
            ->where('idItem >', 0)
            ->set('esMasVendida', 0)
            ->update();

        // Marcar la promoción seleccionada, si corresponde.
        if (!empty($idItem)) {
            $promocionModel->update($idItem, [
                'esMasVendida' => 1
            ]);
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'La promoción más vendida se guardó correctamente.');
    }
    public function guardarPromoDelDia()
    {
        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();

        $promosSeleccionadas = $this->request->getPost('promoDelDia');

        // Normalizar la selección recibida.
        if (!is_array($promosSeleccionadas)) {
            $promosSeleccionadas = [];
        }

        // Verificar que todas las promociones seleccionadas existan y estén activas.
        foreach ($promosSeleccionadas as $idItem) {
            if (!ctype_digit((string) $idItem) || (int) $idItem <= 0) {
                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'La selección de promociones no es válida.');
            }

            $promocion = $promocionModel->find((int) $idItem);
            $item = $itemPedidoModel->find((int) $idItem);

            if (!$promocion || !$item || $item['activo'] != 1) {
                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'Una de las promociones seleccionadas no existe o está desactivada.');
            }
        }

        // Quitar la marca de promoción del día de todas las promociones.
        $promocionModel
            ->where('idItem >', 0)
            ->set('esPromoDia', 0)
            ->update();

        // Marcar las promociones seleccionadas.
        foreach ($promosSeleccionadas as $idItem) {
            $promocionModel->update((int) $idItem, [
                'esPromoDia' => 1
            ]);
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Las promociones del día se guardaron correctamente.');
    }

    public function editarPromocion(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        // Obtener los datos generales de la promoción.
        $datosPromocion = $itemPedidoModel->find($idItem);

        if (!$datosPromocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'No se encontraron los datos de la promoción.');
        }

        // Agregar los datos propios de la promoción.
        $datosPromocion['incluyeBebida'] = $promocion['incluyeBebida'];
        $datosPromocion['incluyeEmpanadas'] = $promocion['incluyeEmpanadas'];

        $datos['promocion'] = $datosPromocion;

        return view('administrador/editar_promocion', $datos);
    }
    public function actualizarPromocion(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción.
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        // Verificar que existan los datos generales.
        $promocionActual = $itemPedidoModel->find($idItem);

        if (!$promocionActual) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'No se encontraron los datos de la promoción.');
        }

        // Validar los datos del formulario.
        $reglas = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'El nombre de la promoción es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede superar los 100 caracteres.'
                ]
            ],

            'descripcion' => [
                'rules' => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => 'La descripción no puede superar los 500 caracteres.'
                ]
            ],

            'precio' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'El precio es obligatorio.',
                    'numeric' => 'El precio debe ser un número válido.',
                    'greater_than' => 'El precio debe ser mayor que 0.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Datos generales de la promoción.
        $datosItem = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio')
        ];

        // Datos específicos de la promoción.
        $datosPromocion = [
            'incluyeBebida' => $this->request->getPost('incluyeBebida') ? 1 : 0,
            'incluyeEmpanadas' => $this->request->getPost('incluyeEmpanadas') ? 1 : 0
        ];

        // Actualizar ambas tablas dentro de una transacción.
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $actualizadoItem = $itemPedidoModel->update($idItem, $datosItem);
            $actualizadaPromocion = $promocionModel->update($idItem, $datosPromocion);

            if (
                !$actualizadoItem ||
                !$actualizadaPromocion ||
                $db->transStatus() === false
            ) {
                $db->transRollback();

                log_message(
                    'error',
                    'No se pudieron actualizar todos los datos de la promoción ID ' . $idItem
                );

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo actualizar la promoción. Intentá nuevamente.');
            }

            if (!$db->transCommit()) {
                log_message(
                    'error',
                    'No se pudo confirmar la actualización de la promoción ID ' . $idItem
                );

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo actualizar la promoción. Intentá nuevamente.');
            }

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Error al actualizar la promoción ID ' . $idItem . ': ' . $e->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la promoción.');
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Promoción actualizada correctamente.');
    }

    
    public function editarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción.
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        // Obtener los datos generales de la promoción.
        $datosPromocion = $itemPedidoModel->find($idItem);

        if (!$datosPromocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'No se encontraron los datos de la promoción.');
        }

        $datos['producto'] = $datosPromocion;
        $datos['tipo'] = 'promocion';

        return view('administrador/editar_imagen', $datos);
    }

    public function actualizarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción.
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        // Obtener los datos actuales.
        $promocionActual = $itemPedidoModel->find($idItem);

        if (!$promocionActual) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'No se encontraron los datos de la promoción.');
        }

        // Validar imagen.
        $reglas = [
            'imagen' => [
                'rules' => 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]|max_size[imagen,3072]',
                'errors' => [
                    'uploaded' => 'Debés seleccionar una imagen.',
                    'is_image' => 'El archivo seleccionado no es una imagen válida.',
                    'mime_in' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
                    'max_size' => 'La imagen no puede superar los 3 MB.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imagen = $this->request->getFile('imagen');
        $imagenAnterior = $promocionActual['urlImagen'] ?? null;
        $nombreImagen = $imagen->getRandomName();
        $rutaImagenNueva = FCPATH . 'img/' . $nombreImagen;

        // Guardar la nueva imagen.
        try {
            $imagen->move(FCPATH . 'img', $nombreImagen);
        } catch (\Throwable $e) {
            log_message('error', 'Error al guardar imagen de promoción: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar la nueva imagen.');
        }

        // Actualizar la base de datos.
        try {
            $actualizado = $itemPedidoModel->update($idItem, [
                'urlImagen' => $nombreImagen
            ]);
        } catch (\Throwable $e) {
            $actualizado = false;

            log_message('error', 'Error al actualizar imagen de promoción: ' . $e->getMessage());
        }

        // Si falla la actualización, conservar la imagen anterior.
        if (!$actualizado) {
            if (is_file($rutaImagenNueva)) {
                @unlink($rutaImagenNueva);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la imagen. Intentá nuevamente.');
        }

        // Eliminar la imagen anterior después de actualizar la base de datos.
        if (!empty($imagenAnterior) && $imagenAnterior !== $nombreImagen) {
            $rutaImagenAnterior = FCPATH . 'img/' . $imagenAnterior;

            if (is_file($rutaImagenAnterior)) {
                @unlink($rutaImagenAnterior);
            }
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Imagen actualizada correctamente.');
    }

    
    public function desactivarPromocion(int $idItem)
    {
        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Verificar que el registro sea realmente una promoción.
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        try {
            // Desactivar la promoción.
            $resultado = $itemPedidoModel->update($idItem, [
                'activo' => 0
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo desactivar la promoción ID ' . $idItem);

                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'No se pudo desactivar la promoción. Intentá nuevamente.');
            }

            // Quitar las marcas de promoción del día y más vendida.
            $resultadoMarcas = $promocionModel->update($idItem, [
                'esPromoDia' => 0,
                'esMasVendida' => 0
            ]);

            if (!$resultadoMarcas) {
                log_message('error', 'No se pudieron actualizar las marcas de la promoción ID ' . $idItem);

                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'La promoción se desactivó, pero no se pudieron actualizar sus marcas.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al desactivar la promoción ID ' . $idItem . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'Ocurrió un error al desactivar la promoción.');
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Promoción desactivada correctamente.');
    }

    public function activarPromocion(int $idItem)
    {
        $promocionModel = new PromocionModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Verificar que el registro sea realmente una promoción.
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        try {
            $resultado = $itemPedidoModel->update($idItem, [
                'activo' => 1
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo activar la promoción ID ' . $idItem);

                return redirect()->to(base_url('admin/promociones'))
                    ->with('error', 'No se pudo activar la promoción. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al activar la promoción ID ' . $idItem . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'Ocurrió un error al activar la promoción.');
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Promoción activada correctamente.');
    }

}    
