<?php

namespace App\Controllers;

use App\Models\AdministradorModel;

class Administrador extends BaseController
{
    public function login()
    {
        return view('administrador/login');
    }

    public function validarLogin()
    {
        $rules = [
            'email' => [
                'rules' => 'required|regex_match[/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u]|is_not_unique[administrador.email]',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'regex_match' => 'Ingresá un email válido',
                    'is_not_unique' => 'El email no está registrado'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria',
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('erroresLoginAdmin', $this->validator->getErrors());
        }

        $administradorModel = new AdministradorModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $administrador = $administradorModel
            ->where('email', $email)
            ->first(); 

        if (
            !$administrador ||
            !password_verify($password, $administrador['contraseña'])
        ) {
            return redirect()->back()
                ->withInput()
                ->with('erroresLoginAdmin', [
                    'password' => 'El correo o la contraseña son incorrectos.'
                ]);
        }

        session()->set([
            'idAdministrador' => $administrador['idAdministrador'],
            'nombreAdministrador' => $administrador['nombre'],
            'idSucursal' => $administrador['idSucursal'],
            'administradorLogueado' => true
        ]);
        // Cerrar la autenticación del cliente
        session()->remove([
            'idCliente',
            'nombre',
            'logueado'
        ]);

        return redirect()->to('/admin/panel');
    }

    public function panel()
    {
        // Verificar que haya un administrador autenticado
        if (!session()->get('administradorLogueado')) {
            return redirect()->to('/admin');
        }

        return view('administrador/panel');
    }
    public function logoutAdmi()
    {
        session()->remove([
            'idAdministrador',
            'nombreAdministrador',
            'idSucursal',
            'administradorLogueado'
        ]);

        return redirect()->to('/admin');
    }
}