<?php

namespace App\Models;

use CodeIgniter\Model;

class PromocionModel extends Model
{
    protected $table = 'promocion';
    protected $primaryKey = 'idItem';

    protected $allowedFields = [
        'idItem',
        'esPromoDia',
        'incluyeBebida',
        'incluyeEmpanadas'
    ];

    public function obtenerPromociones()
    {
        return $this->select('item_pedido.*, promocion.incluyeBebida, promocion.incluyeEmpanadas')
            ->join(
                'item_pedido',
                'item_pedido.idItem = promocion.idItem',
            )
            ->where('item_pedido.activo', 1)
            ->orderBy('item_pedido.nombre', 'ASC')
            ->findAll();
    }

    public function obtenerPromoDelDia()
    {
        return $this->select('item_pedido.*, promocion.incluyeBebida, promocion.incluyeEmpanadas')
            ->join(
                'item_pedido',
                'item_pedido.idItem = promocion.idItem'
            )
            ->where('item_pedido.activo', 1)
            ->where('promocion.esPromoDia', 1)
            ->findAll();
    }
}