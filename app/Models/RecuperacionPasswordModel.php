<?php

namespace App\Models;

use CodeIgniter\Model;

class RecuperacionPasswordModel extends Model
{
    protected $table = 'recuperacion_password';
    protected $primaryKey = 'idRecuperacion';

    protected $allowedFields = [
        'idCliente',
        'codigo',
        'vence',
        'usado'
    ];
}