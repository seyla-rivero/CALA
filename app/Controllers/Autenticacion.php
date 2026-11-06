<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\RecuperacionPasswordModel;

class Autenticacion extends BaseController
{
    public function validarLogin()
    {
    $rules = [
        'email' => [
            'rules' => 'required|regex_match[/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u]|is_not_unique[cliente.email]',
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
                ->with('erroresLogin', $this->validator->getErrors())
                ->with('modal', 'login');
        }

        $clienteModel = new ClienteModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $cliente = $clienteModel ->where('email', $email) ->first();

        if (!password_verify($password, $cliente['contraseña'])) {
        return redirect()->back()
            ->withInput()
            ->with('erroresLogin', [
                'password' => 'La contraseña es incorrecta'
            ])
            ->with('modal', 'login');
        }

        session()->set([
            'idCliente' => $cliente['idCliente'],
            'nombre' => $cliente['nombre'],
            'logueado' => true
        ]);
        // Cerrar la autenticación del administrador
        session()->remove([
            'idAdministrador',
            'nombreAdministrador',
            'idSucursal',
            'administradorLogueado'
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
        'apellido' => [
            'rules' => 'required|min_length[3]',
            'errors' => [
                'required' => 'El apellido es obligatorio',
                'min_length' => 'El apellido debe tener al menos 3 caracteres'
            ]
        ],
        'email' => [
            'rules' => 'required|regex_match[/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u]|is_unique[cliente.email]',
            'errors' => [
                'required' => 'El email es obligatorio',
                'regex_match' => 'Ingresá un email válido',
                'is_unique' => 'Este correo ya está registrado'
            ]
        ],
        'telefono' => [
            'rules' => 'required|numeric|min_length[10]',
            'errors' => [
                'required' => 'El teléfono es obligatorio',
                'numeric' => 'El teléfono debe contener solamente números',
                'min_length' => 'El teléfono debe tener al menos 10 dígitos'
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
            ->with('erroresRegistro', $this->validator->getErrors())
            ->with('modal', 'registro');
        }

        $clienteModel = new ClienteModel();

        $clienteModel->save([
            'nombre'     => $this->request->getPost('nombre'),
            'apellido'   => $this->request->getPost('apellido'),
            'email'      => $this->request->getPost('email'),
            'telefono'   => $this->request->getPost('telefono'),
            'contraseña' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            )
        ]);

        $cliente = $clienteModel ->where('email', $this->request->getPost('email'))->first();

        session()->set([
            'idCliente' => $cliente['idCliente'],
            'nombre'    => $cliente['nombre'],
            'logueado'  => true
        ]);
        // Cerrar la autenticación del administrador
        session()->remove([
            'idAdministrador',
            'nombreAdministrador',
            'idSucursal',
            'administradorLogueado'
        ]);

        return redirect()->to('/')
            ->with('success', 'Cuenta creada correctamente');

    }
        
    public function logout()
    {
        session()->remove([
            'idCliente',
            'nombre',
            'logueado'
        ]);

        return redirect()->to('/');
    }
    public function solicitarRecuperacion()
    {
        $emailIngresado = trim($this->request->getPost('emailRecuperacion'));

        // Validar el correo ingresado
        $rules = [
            'emailRecuperacion' => 'required|valid_email'
        ];

        $messages = [
            'emailRecuperacion' => [
                'required' => 'Ingresá tu correo electrónico.',
                'valid_email' => 'Ingresá un correo electrónico válido.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->with('errorRecuperacion', $this->validator->getError('emailRecuperacion'))
                ->with('emailRecuperacion', $emailIngresado)
                ->with('modal', 'recuperar');
        }

        $clienteModel = new ClienteModel();
        $recuperacionModel = new RecuperacionPasswordModel();

        // Buscar el cliente por su correo
        $cliente = $clienteModel
            ->where('email', $emailIngresado)
            ->first();

        // Mensaje genérico para no revelar si existe una cuenta
        $mensaje = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';

        if (!$cliente) {
            return redirect()->back()
                ->with('mensajeRecuperacion', $mensaje)
                ->with('modal', 'recuperar');
        }

        // Generar un token aleatorio
        $token = bin2hex(random_bytes(32));

        // Guardar el hash del token, no el token original
        $tokenHash = hash('sha256', $token);

        // Eliminar solicitudes anteriores del mismo cliente
        $recuperacionModel
            ->where('idCliente', $cliente['idCliente'])
            ->delete();

        // Guardar el nuevo token con vencimiento de 30 minutos
        $idRecuperacion = $recuperacionModel->insert([
            'idCliente' => $cliente['idCliente'],
            'token'     => $tokenHash,
            'expiraEn'  => date('Y-m-d H:i:s', time() + 1800),
            'creadoEn'  => date('Y-m-d H:i:s')
        ]);

        if (!$idRecuperacion) {
            log_message('error', 'No se pudo guardar el token de recuperación.');

            return redirect()->back()
                ->with('errorRecuperacion', 'No se pudo procesar la solicitud. Intentá nuevamente.')
                ->with('modal', 'recuperar');
        }

        // Crear el enlace de recuperación
        $url = site_url('restablecer-password/' . $token);

        // Preparar el correo electrónico
        $email = service('email');

        $email->setTo($cliente['email']);
        $email->setSubject('Recuperación de contraseña - CALA Delivery');

        $mensajeCorreo = '
            <h2>Recuperación de contraseña</h2>
            <p>Hola ' . esc($cliente['nombre']) . ',</p>
            <p>Recibimos una solicitud para restablecer tu contraseña.</p>
            <p>Hacé clic en el siguiente enlace:</p>
            <p><a href="' . esc($url) . '">Restablecer mi contraseña</a></p>
            <p>Este enlace tiene una validez de 30 minutos.</p>
            <p>Si no solicitaste este cambio, podés ignorar este correo.</p>
            <p>Saludos,<br>CALA Delivery Sandwich</p>
        ';

        $email->setMessage($mensajeCorreo);

        // Enviar el correo
        if (!$email->send()) {

            // Eliminar el token si no se pudo enviar el correo
            $recuperacionModel->delete($idRecuperacion);

            log_message('error', 'No se pudo enviar el correo de recuperación.');

            return redirect()->back()
                ->with('errorRecuperacion', 'No pudimos enviar el correo. Intentá nuevamente más tarde.')
                ->with('modal', 'recuperar');
        }

        return redirect()->back()
            ->with('mensajeRecuperacion', $mensaje)
            ->with('modal', 'recuperar');
    }

    public function mostrarRestablecerPassword(string $token)
    {
        $recuperacionModel = new RecuperacionPasswordModel();

        // Buscar el token mediante su hash
        $tokenHash = hash('sha256', $token);

        $recuperacion = $recuperacionModel
            ->where('token', $tokenHash)
            ->first();

        // Comprobar que el token exista y no haya vencido
        if (
            !$recuperacion ||
            strtotime($recuperacion['expiraEn']) < time()
        ) {
            return redirect()->to('/')
                ->with('error', 'El enlace de recuperación es inválido o ha vencido.');
        }

        // Mostrar el formulario para cambiar la contraseña
        return view('componentes/restablecerPassword', [
            'token' => $token
        ]);
    }

    
    public function actualizarPassword()
    {
        $token = $this->request->getPost('token');

        $password = $this->request->getPost('password');
        $confirmar = $this->request->getPost('confirmar');

        // Comprobar que se haya recibido el token
        if (empty($token)) {
            return redirect()->to('/')
                ->with('error', 'El enlace de recuperación es inválido o ha vencido.');
        }

        // Validar las contraseñas
        $rules = [
            'password' => 'required|min_length[6]',
            'confirmar' => 'required|matches[password]'
        ];

        $messages = [
            'password' => [
                'required' => 'Ingresá una contraseña.',
                'min_length' => 'La contraseña debe tener al menos 6 caracteres.'
            ],
            'confirmar' => [
                'required' => 'Confirmá tu contraseña.',
                'matches' => 'Las contraseñas no coinciden.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return view('componentes/restablecerPassword', [
                'token' => $token,
                'erroresPassword' => $this->validator->getErrors()
            ]);
        }

        $recuperacionModel = new RecuperacionPasswordModel();

        // Buscar el token
        $tokenHash = hash('sha256', $token);

        $recuperacion = $recuperacionModel
            ->where('token', $tokenHash)
            ->first();

        // Comprobar que sea válido y no haya vencido
        if (
            !$recuperacion ||
            strtotime($recuperacion['expiraEn']) < time()
        ) {
            return redirect()->to('/')
                ->with('error', 'El enlace de recuperación es inválido o ha vencido.');
        }

        // Actualizar la contraseña del cliente
        $clienteModel = new ClienteModel();

        $clienteModel->update(
            $recuperacion['idCliente'],
            [
                'contraseña' => password_hash($password, PASSWORD_DEFAULT)
            ]
        );

        // Eliminar el token utilizado
        $recuperacionModel->delete($recuperacion['id']);

        return redirect()->to('/')
            ->with('success', '¡Tu contraseña se actualizó correctamente! Ya podés iniciar sesión.');
    }

}