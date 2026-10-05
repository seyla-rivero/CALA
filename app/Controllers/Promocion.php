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
                'rules' => 'uploaded[urlImagen]|is_image[urlImagen]|mime_in[urlImagen,image/jpg,image/jpeg,image/png,image/webp]|max_size[urlImagen,3072]',
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

        // Datos del formulario
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');
        $precio = $this->request->getPost('precio');

        $incluyeBebida = $this->request->getPost('incluyeBebida') ? 1 : 0;
        $incluyeEmpanadas = $this->request->getPost('incluyeEmpanadas') ? 1 : 0;

        // Imagen
        $imagen = $this->request->getFile('imagen');

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

        // Obtener los datos del producto
        $producto = $itemPedidoModel->find($idItem);

        // Agregar los datos propios de la promoción
        $producto['incluyeBebida'] = $promocion['incluyeBebida'];
        $producto['incluyeEmpanadas'] = $promocion['incluyeEmpanadas'];

        $datos['producto'] = $producto;

        return view('administrador/editar_promocion', $datos);
    }
    public function actualizarPromocion(int $idItem)
    {
        $reglas = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'El nombre del producto es obligatorio.',
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
            ],
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        $datos = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio')
        ];

        $itemPedidoModel->update($idItem, $datos);

        $datosPromocion = [
            'incluyeBebida'    => $this->request->getPost('incluyeBebida') ? 1 : 0,
            'incluyeEmpanadas' => $this->request->getPost('incluyeEmpanadas') ? 1 : 0
        ];

        $promocionModel->update($idItem, $datosPromocion);

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Promoción actualizada correctamente.');
    }

    public function editarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        $datos['producto'] = $itemPedidoModel->find($idItem);
        $datos['tipo'] = 'promocion';

        return view('administrador/editar_imagen', $datos);
    }

    public function actualizarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        // Verificar que exista como promoción
        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promo no existe.');
        }

        $imagen = $this->request->getFile('imagen');

        // Validar imagen
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

        // Obtener imagen anterior
        $promocionActual = $itemPedidoModel->find($idItem);
        $imagenAnterior = $promocionActual['urlImagen'];

        // Generar un nombre nuevo para evitar conflictos
        $nombreImagen = $imagen->getRandomName();

        // Mover imagen a public/img
        $imagen->move(FCPATH . 'img', $nombreImagen);

        // Actualizar nombre de imagen en la base de datos
        $itemPedidoModel->update($idItem, [
            'urlImagen' => $nombreImagen
        ]);

        // Eliminar imagen anterior si existe
        if (!empty($imagenAnterior)) {
            $rutaImagenAnterior = FCPATH . 'img/' . $imagenAnterior;

            if (file_exists($rutaImagenAnterior)) {
                unlink($rutaImagenAnterior);
            }
        }

        return redirect()->to(base_url('admin/promociones'))
            ->with('mensajeExito', 'Imagen actualizada correctamente.');
    }

    public function desactivarPromocion(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        $itemPedidoModel->update($idItem, [
            'activo' => 0
        ]);

        $promocionModel->update($idItem, [
            'esPromoDia' => 0,
            'esMasVendida' => 0
        ]);

        return redirect()->to(base_url('admin/promociones'));
    }
    public function activarPromocion(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $promocionModel = new PromocionModel();

        $promocion = $promocionModel->find($idItem);

        if (!$promocion) {
            return redirect()->to(base_url('admin/promociones'))
                ->with('error', 'La promoción no existe.');
        }

        $itemPedidoModel->update($idItem, [
            'activo' => 1
        ]);

        return redirect()->to(base_url('admin/promociones'));
    }
}    
