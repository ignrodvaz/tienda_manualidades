<?php

namespace App\Controllers;

use App\Models\EventoModel;
use CodeIgniter\HTTP\ResponseInterface;

class EventoController extends BaseController
{
    public function index()
    {
        return view('calendario'); // Cargar la vista del calendario
    }

    // Obtener eventos desde la BD
    public function obtenerEventos()
    {
        $eventoModel = new EventoModel();
        $eventos = $eventoModel->where('FECHA_BAJA', null)->findAll(); // Filtrar eventos activos

        $data = [];
        foreach ($eventos as $evento) {
            $data[] = [
                'id' => $evento['PK_ID_EVENTO'],
                'title' => $evento['TITULO'],
                'start' => $evento['FECHA_INICIO'],
                'end' => $evento['FECHA_FINAL'],
                'descripcion' => $evento['DESCRIPCION_ES']
            ];
        }

        return $this->response->setJSON($data);
    }


    // Añadir evento
    public function anadirEvento()
    {
        $eventoModel = new EventoModel();

        $title = $this->request->getPost('title');
        $start = $this->request->getPost('start');
        $end = $this->request->getPost('end');
        $descripcion = $this->request->getPost('descripcion');

        $data = [
            'TITULO' => $title,
            'FECHA_INICIO' => $start,
            'FECHA_FINAL' => $end,
            'DESCRIPCION_ES' => $descripcion
        ];

        if($eventoModel->insert($data)){
            $insertID = $eventoModel->insertID();
            return $this->response->setJSON([
                'status' => 'success',
                'id' => $insertID
            ]);
        }else{
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo insertar el evento'
            ]);
        }

    }

    // Eliminar evento (marcarlo con FECHA_BAJA)
    public function eliminarEvento()
    {
        $eventoModel = new EventoModel();
        $id = $this->request->getPost('id'); // 🔹 Recibir el ID correctamente

        // 🔍 Depuración: Si no llega el ID, devuelve un error
        if (!$id) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID no proporcionado']);
        }

        try {
            if (!$eventoModel->find($id)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Evento no encontrado']);
            }

            // 🔹 En lugar de eliminar, marcamos como dado de baja
            $eventoModel->update($id, ['FECHA_BAJA' => date('Y-m-d H:i:s')]);

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }


}
