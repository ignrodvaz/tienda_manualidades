<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function index()
    {
        // Mostrar el formulario de inicio de sesión
        return view('login_form');
    }

    public function authenticate()
    {
        helper(['form', 'url']); // Carga los helpers necesarios para trabajar con formularios y URLs.
        $session = session(); // Inicia una sesión para el usuario.

        // Configuración de las reglas de validación del formulario.
        $rules = [
            'email' => 'required|valid_email', // El correo es obligatorio y debe ser válido.
            'password' => 'required', // La contraseña es obligatoria.
        ];

        // Si la validación falla, volvemos a mostrar el formulario con los errores.
        if (!$this->validate($rules)) {
            return view('login_form', [
                'validation' => $this->validator, // Pasamos los errores de validación a la vista.
            ]);
        }

        // Si la validación pasa, verificamos las credenciales.
        $userModel = new UserModel();
        $user = $userModel->findByEmail($this->request->getPost('email')); // Buscamos al usuario por su correo.

        if ($user && password_verify($this->request->getPost('password'), $user['CONTRASENA'])) {
            // Si las credenciales son correctas, guardamos datos del usuario en la sesión.
            $session->set([
                'id' => $user['PK_ID_CLIENTE'],           // ID del usuario.
                'name' => $user['NOMBRE'],       // Nombre del usuario.
                'email' => $user['EMAIL'],     // Correo del usuario.
                'isLoggedIn' => true,          // Bandera para indicar que está logueado.
                'created_at' => $user['created_at'], // Fecha de registro del usuario.
            ]);

            // Redirigimos a la página de inicio con un mensaje de éxito.
            return redirect()->to('/cliente')->with('success', 'Inicio de sesión exitoso.');
        }

        // Si las credenciales son incorrectas, mostramos un mensaje de error.
        return redirect()->to('/login')->with('error', 'Correo o contraseña incorrectos.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}