<?php

namespace App\Controllers;

use App\Models\PromocionModel;

class Promocion extends BaseController
{
    public function promociones()
        {
            $promocionesModel = new PromocionModel();

            $datos['promociones'] = $promocionesModel->obtenerPromociones();

            return view('administrador/promociones', $datos);
        }
}    