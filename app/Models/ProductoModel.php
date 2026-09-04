<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idItem';

    protected $allowedFields = [
        'idItem'
    ];

    public function obtenerProductosMenu()
    {
        return $this->select('item_pedido.*')
            ->join('item_pedido', 'item_pedido.idItem = producto.idItem')
            ->where('item_pedido.activo', 1)
            ->findAll();
    }
}