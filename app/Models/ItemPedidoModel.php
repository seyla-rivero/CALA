<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemPedidoModel extends Model
{
    protected $table = 'item_pedido';
    protected $primaryKey = 'idItem';

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'urlImagen',
        'precio',
        'activo',
        'idCategoria'
    ];
}