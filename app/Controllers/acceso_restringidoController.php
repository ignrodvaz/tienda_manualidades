<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class acceso_restringidoController extends Controller
{
    public function index()
    {
        // Mostrar el formulario de inicio de sesión
        return view('acceso_restringido');
    }
}