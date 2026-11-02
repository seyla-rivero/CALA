<?php

namespace App\Models;

use CodeIgniter\Model;

class RecuperacionPasswordModel extends Model
{
    protected $table = 'recuperacion_password';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idCliente',
        'token',
        'expiraEn',
        'creadoEn'
    ];

    protected $useTimestamps = false;
}
