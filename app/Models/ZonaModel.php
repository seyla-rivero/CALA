<?php

namespace App\Models;

use CodeIgniter\Model;

class ZonaModel extends Model
{
    protected $table = 'zona';
    protected $primaryKey = 'idZona';

    protected $allowedFields = [
        'nombre',
        'costoEnvio',
        'activo',
        'idSucursal'
    ];

    public function obtenerZonasAdmin($idSucursal)
    {
        return $this->where('idSucursal', $idSucursal)
                    ->findAll();
    }
}