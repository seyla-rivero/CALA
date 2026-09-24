<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministradorModel extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'idAdministrador';

    protected $allowedFields = [
        'nombre',
        'email',
        'contraseña',
        'idSucursal'
    ];
}