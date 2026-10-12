<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ItemPedidoModel;
use App\Models\CategoriaModel;

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
        $categoriaModel = new CategoriaModel();

        $datos['categorias'] = $categoriaModel->findAll();

        return view('administrador/agregar_producto', $datos);
    }

    public function guardarProducto()
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

        // Obtener y guardar imagen
        $imagen = $this->request->getFile('imagen');
        $nombreImagen = $imagen->getRandomName();
        $rutaImagen = FCPATH . 'img/' . $nombreImagen;

        try {
            $imagen->move(FCPATH . 'img', $nombreImagen);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar la imagen. Intentá nuevamente.');
        }

        // Datos del producto
        $datos = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'urlImagen'   => $nombreImagen,
            'precio'      => $this->request->getPost('precio'),
            'activo'      => 1,
            'idCategoria' => $this->request->getPost('idCategoria')
        ];

        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Iniciar la transacción.
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Guardar los datos generales en item_pedido.
            if ($itemPedidoModel->insert($datos) === false) {
                throw new \RuntimeException('No se pudo guardar el registro general.');
            }

            $idItem = $itemPedidoModel->getInsertID();

            if (!$idItem) {
                throw new \RuntimeException('No se pudo obtener el identificador.');
            }

            // Registrar el producto en la tabla producto.
            if ($productoModel->insert([
                'idItem' => $idItem
            ]) === false) {
                throw new \RuntimeException('No se pudo registrar el producto.');
            }

            // Confirmar ambas inserciones.
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Falló la transacción.');
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            $db->transRollback();

            // Eliminar la imagen si se revirtieron las inserciones.
            if (is_file($rutaImagen)) {
                @unlink($rutaImagen);
            }

            log_message('error', 'Error al guardar producto: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar el producto. Intentá nuevamente.');
        }

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

        $categoriaModel = new CategoriaModel();
        $datos['categorias'] = $categoriaModel->findAll();

        return view('administrador/editar_producto', $datos);
    }

    public function actualizarProducto(int $idItem)
    {
        $productoModel = new ProductoModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Verificar que el registro corresponda a un producto.
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to('admin/productos')
                ->with('error', 'El producto no existe.');
        }

        $rules = [
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

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'idCategoria' => $this->request->getPost('idCategoria')
        ];

        if (!$itemPedidoModel->update($idItem, $datos)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el producto. Intentá nuevamente.');
        }

        return redirect()->to(base_url('admin/productos'))
            ->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function editarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Verificar que exista como producto.
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        // Obtener los datos generales del producto.
        $datosProducto = $itemPedidoModel->find($idItem);

        if (!$datosProducto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'No se encontraron los datos del producto.');
        }

        $datos['producto'] = $datosProducto;
        $datos['tipo'] = 'producto';

        return view('administrador/editar_imagen', $datos);
    }
    
    public function actualizarImagen(int $idItem)
    {
        $itemPedidoModel = new ItemPedidoModel();
        $productoModel = new ProductoModel();

        // Verificar que exista como producto.
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
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

        // Obtener los datos actuales.
        $productoActual = $itemPedidoModel->find($idItem);

        if (!$productoActual) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'No se encontraron los datos del producto.');
        }

        $imagen = $this->request->getFile('imagen');
        $imagenAnterior = $productoActual['urlImagen'] ?? null;
        $nombreImagen = $imagen->getRandomName();
        $rutaImagenNueva = FCPATH . 'img/' . $nombreImagen;

        // Guardar la nueva imagen.
        try {
            $imagen->move(FCPATH . 'img', $nombreImagen);
        } catch (\Throwable $e) {
            log_message('error', 'Error al guardar imagen: ' . $e->getMessage());

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

            log_message('error', 'Error al actualizar imagen del producto: ' . $e->getMessage());
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

        return redirect()->to(base_url('admin/productos'))
            ->with('mensaje', 'Imagen actualizada correctamente.');
    }

    public function desactivarProducto(int $idItem)
    {
        $productoModel = new ProductoModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Verificar que el registro sea realmente un producto.
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        try {
            $resultado = $itemPedidoModel->update($idItem, [
                'activo' => 0
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo desactivar el producto ID ' . $idItem);

                return redirect()->to(base_url('admin/productos'))
                    ->with('error', 'No se pudo desactivar el producto. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al desactivar el producto ID ' . $idItem . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'Ocurrió un error al desactivar el producto.');
        }

        return redirect()->to(base_url('admin/productos'))
            ->with('mensajeExito', 'Producto desactivado correctamente.');
    }

    public function activarProducto(int $idItem)
    {
        $productoModel = new ProductoModel();
        $itemPedidoModel = new ItemPedidoModel();

        // Verificar que el registro sea realmente un producto.
        $producto = $productoModel->find($idItem);

        if (!$producto) {
            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'El producto no existe.');
        }

        try {
            $resultado = $itemPedidoModel->update($idItem, [
                'activo' => 1
            ]);

            if (!$resultado) {
                log_message('error', 'No se pudo activar el producto ID ' . $idItem);

                return redirect()->to(base_url('admin/productos'))
                    ->with('error', 'No se pudo activar el producto. Intentá nuevamente.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al activar el producto ID ' . $idItem . ': ' . $e->getMessage());

            return redirect()->to(base_url('admin/productos'))
                ->with('error', 'Ocurrió un error al activar el producto.');
        }

        return redirect()->to(base_url('admin/productos'))
            ->with('mensajeExito', 'Producto activado correctamente.');
    }

}