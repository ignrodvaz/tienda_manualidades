<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        // Mostrar el formulario de registro
        return view('register_form');
    }

    public function authenticate()
    {
        $session = session();
        $validation = \Config\Services::validation();

        $rules = [
            'nombre' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'El nombre es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El correo electrónico es obligatorio.',
                    'valid_email' => 'Debes ingresar un correo electrónico válido.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 8 caracteres.'
                ]
            ],
            'confirm-password' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ],
            'telefono' => [
                'rules' => 'required|numeric|min_length[9]',
                'errors' => [
                    'required' => 'El teléfono es obligatorio.',
                    'numeric' => 'El teléfono solo debe contener números.',
                    'min_length' => 'El teléfono debe tener al menos 9 dígitos.'
                ]
            ],
            'direccion' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'La dirección es obligatoria.'
                ]
            ],
            'toc' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debes aceptar los términos y condiciones.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model = new UserModel();

        $data = [
            'NOMBRE' => $this->request->getVar('nombre'),
            'EMAIL' => $this->request->getVar('email'),
            'CONTRASENA' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'TELEFONO' => $this->request->getVar('telefono'),
            'DIRECCION' => $this->request->getVar('direccion'),
        ];

        $model->save($data);
        $session->setFlashdata('msg', 'Registro exitoso. Ahora puedes iniciar sesión.');
        return redirect()->to('/login');
    }

}