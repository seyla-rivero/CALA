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
}