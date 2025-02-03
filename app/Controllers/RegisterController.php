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