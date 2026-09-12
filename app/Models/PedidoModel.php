<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';

    protected $allowedFields = [
        'idCliente',
        'fecha',
        'estado',
        'tipoEntrega',
        'direccionEntrega',
        'subTotal',
        'costoEnvio',
        'total'
    ];
}