<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table = 'sucursal';
    protected $primaryKey = 'idSucursal';

    protected $allowedFields = [
        'nombre',
        'direccion',
        'telefono',
        'alias',
        'cbu',
        'titular',
        'activo',
        'horario',
        'ubicacionMapa'

    ];
}