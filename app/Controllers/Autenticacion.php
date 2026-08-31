<?php

namespace App\Controllers;

use App\Models\ClienteModel;

class Autenticacion extends BaseController
{
    public function validarLogin()
    {
    $rules = [
        'email' => [
            'rules' => 'required|valid_email|is_not_unique[usuarios.email]',
            'errors' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Ingresá un email válido',
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
                ->with('validation', $this->validator) 
                ->with('modal', 'login');
        }

        $clienteModel = new ClienteModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($email === '' || $password === '') {
        return redirect()->back()
            ->withInput()
            ->with('validation', $this->validator)
            ->with('modal', 'login');
        }

        $cliente = $clienteModel ->where('email', $email) ->first();

        if (!$cliente) {
            return redirect()->back()
               ->withInput()
               ->with('error_login', 'El email no está registrado')
               ->with('modal', 'login');
                
        }

        if (!$this->validator->getErrors() &&
           !password_verify($password, $cliente['password'])
        ) {
           return redirect()->back()
                ->withInput()
               ->with('error_login', 'Contraseña incorrecta')
                ->with('modal', 'login');
        }

        session()->set([
            'idCliente' => $cliente['idCliente'],
            'nombre' => $cliente['nombre'],
            'logueado' => true
        ]);

        return redirect()->to('/');
    }
    public function validarRegistro()
    {
    $rules = [
    'nombre' => [
        'rules' => 'required|min_length[3]',
        'errors' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres'
        ]
    ],
    'email' => [
        'rules' => 'required|valid_email|is_unique[usuarios.email]',
        'errors' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Ingresá un email válido',
            'is_unique' => 'Este correo ya está registrado'
        ]
    ],
    'password' => [
        'rules' => 'required|min_length[6]',
        'errors' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ]
    ],
    'confirmar' => [
        'rules' => 'required|matches[password]',
        'errors' => [
            'required' => 'Debés confirmar la contraseña',
            'matches' => 'Las contraseñas no coinciden'
        ]
    ]
    ];

    if (!$this->validate($rules)) {
    return redirect()->back()
        ->withInput()
        ->with('errors', $this->validator->getErrors())
        ->with('modal', 'registro');
    }

    $clienteModel = new ClienteModel();
     
    $nombre = $this->request->getPost('nombre');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    }
}