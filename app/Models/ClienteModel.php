<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'contraseña',
        'telefono'
    ];
}