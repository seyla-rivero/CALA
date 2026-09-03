<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string{
        return view('cliente/paginaPrincipal');
    }
    public function menu(): string{
        return view('cliente/menu');
    }
}
