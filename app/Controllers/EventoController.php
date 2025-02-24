<?php

namespace App\Controllers;

use App\Models\EventoModel;

class EventoController extends BaseController
{
    public function index()
    {
        $eventoModel = new EventoModel();
        $data['eventos'] = $eventoModel->findAll();
        return view('calendario', $data);
    }

    public function anadirEvento(){

    }

    public function eliminarEvento(){

    }
}