<?php

namespace App\Models;

use CodeIgniter\Model;

class PromocionModel extends Model
{
    protected $table = 'promocion';
    protected $primaryKey = 'idItem';

    protected $allowedFields = [
        'idItem'
    ];
}