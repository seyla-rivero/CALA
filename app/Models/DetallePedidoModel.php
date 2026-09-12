<?php

namespace App\Models;

use CodeIgniter\Model;

class DetallePedidoModel extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'idDetallePedido';

    protected $allowedFields = [
        'idPedido',
        'idItem',
        'cantidad',
        'precioUnitario',
        'subTotal',
        'comentario'
    ];
}