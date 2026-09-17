<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';

    protected $allowedFields = [
        'idCliente',
        'idSucursal',
        'idZona',
        'fecha',
        'estado',
        'tipoEntrega',
        'direccionEntrega',
        'metodoPago',
        'estadoPago',
        'subTotal',
        'costoEnvio',
        'total'
    ];
}