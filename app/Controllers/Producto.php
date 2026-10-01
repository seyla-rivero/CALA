<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ItemPedidoModel;

class Producto extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();

        $datos['productos'] = $productoModel->obtenerTodosLosProductos();

        return view('administrador/productos', $datos);
    }

    public function agregarProducto()
    {
        return view('administrador/agregar_producto');
    }

    public function guardar()
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

            'idCategoria' => [
                'rules' => 'required|is_not_unique[categoria.idCategoria]',
                'errors' => [
                    'required' => 'Debés seleccionar una categoría.',
                    'is_not_unique' => 'La categoría seleccionada no es válida.'
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

        // Validar
        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Obtener imagen
        $imagen = $this->request->getFile('imagen');

        // Generar nombre único
        $nombreImagen = $imagen->getRandomName();

        // Guardar imagen en public/img
        $imagen->move(FCPATH . 'img', $nombreImagen);

        // Datos del producto
        $datos = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'urlImagen'   => $nombreImagen,
            'precio'      => $this->request->getPost('precio'),
            'activo'      => 1,
            'idCategoria' => $this->request->getPost('idCategoria')
        ];

        // Guardar en item_pedido
        $itemPedidoModel = new ItemPedidoModel();

        $itemPedidoModel->insert($datos);

        // Obtener el idItem generado
        $idItem = $itemPedidoModel->getInsertID();

        // Guardar en producto
        $productoModel = new ProductoModel();

        $productoModel->insert([
            'idItem' => $idItem
        ]);

    return redirect()->to(base_url('admin/productos'))
        ->with('mensaje', 'Producto agregado correctamente.');
    }

    public function editarProducto(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Verificar que exista como producto
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        // Obtener los datos del producto
        $datos['producto'] = $itemPedidoModel->find($idItem);

        return view('administrador/editar_producto', $datos);
    }

    public function actualizarProducto(int $idItem)
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

            'idCategoria' => [
                'rules' => 'required|is_not_unique[categoria.idCategoria]',
                'errors' => [
                    'required' => 'Debés seleccionar una categoría.',
                    'is_not_unique' => 'La categoría seleccionada no es válida.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $itemPedidoModel = new ItemPedidoModel();

        $datos = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'idCategoria' => $this->request->getPost('idCategoria')
        ];

        $itemPedidoModel->update($idItem, $datos);

        return redirect()->to(base_url('admin/productos'))
            ->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function editarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Verificar que exista como producto
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        // Obtener los datos del producto
        $datos['producto'] = $itemPedidoModel->find($idItem);

        return view('administrador/editar_imagen', $datos);
    }

    public function actualizarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Verificar que exista como producto
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
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
        $productoActual = $itemPedidoModel->find($idItem);
        $imagenAnterior = $productoActual['urlImagen'];

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

        return redirect()->to(base_url('admin/productos'))
            ->with('mensaje', 'Imagen actualizada correctamente.');
    }

    public function desactivarProducto(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();

        $producto = $itemPedidoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        $itemPedidoModel->update($idItem, [
            'activo' => 0
        ]);

        return redirect()->to(base_url('admin/productos'));
    }
    public function activarProducto(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();

        $producto = $itemPedidoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        $itemPedidoModel->update($idItem, [
            'activo' => 1
        ]);

        return redirect()->to(base_url('admin/productos'));
    }

}