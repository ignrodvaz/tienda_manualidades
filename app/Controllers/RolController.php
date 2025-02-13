<?php

namespace App\Controllers;

use App\Models\RolModel;

class RolController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function index()
    {
        $RolModel = new RolModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $pk_id_rol = $this->request->getVar('PK_ID_ROL');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = $this->request->getVar('perPage') ?: 10;

        $query = $RolModel->select('*');

        if ($estado === 'altas') {
            $query = $query->where('FECHA_BAJA', null);
        } else if ($estado === 'bajas') {
            $query = $query->where('FECHA_BAJA !=', null);
        }

        // Aplicar filtro si se introduce un nombre
        if ($name) {
            $query = $query->like('NOMBRE', $name);
        } else if ($descripcion) {
            $query = $query->like('DESCRIPCION', $descripcion);
        }

        // Configuración de la paginación
        $data['roles'] = $query->paginate($perPage); // Obtener roles paginados
        $data['pager'] = $RolModel->pager; // Pasar el objeto del paginador a la vista
        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['descripcion'] = $descripcion;
        $data['pk_id_rol'] = $pk_id_rol;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista

        return view('listado_rol', $data); // Cargar la vista con los datos
    }

    public function saveRol($PK_ID_ROL = null)
    {
        $RolModel = new RolModel();
        helper(['form', 'url']);

        // Cargar datos del rol si es edición
        $data['rol'] = $PK_ID_ROL ? $RolModel->find($PK_ID_ROL) : null;

        if ($this->request->getMethod() == 'POST') {
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[255]',
                'descripcion' => 'required|min_length[3]|max_length[255]'
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El campo Nombre es obligatorio.',
                    'min_length' => 'El Nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El Nombre no puede exceder los 255 caracteres.',
                ],
                'descripcion' => [
                    'required' => 'El campo Descripción es obligatorio.',
                    'min_length' => 'La Descripción debe tener al menos 3 caracteres.',
                    'max_length' => 'La Descripción no puede exceder los 255 caracteres.',
                ],
            ];

            if (!$this->validate($rules, $messages)) {
                $data['validation'] = $this->validator;

            } else {

                $Roldata = [
                    'NOMBRE' => $this->request->getVar('nombre'),
                    'DESCRIPCION' => $this->request->getVar('descripcion')
                ];

                if ($PK_ID_ROL) {
                    // Actualizar rol existente
                    $RolModel->update($PK_ID_ROL, $Roldata);
                    $message = 'Rol actualizado correctamente.';
                } else {
                    // Crear nuevo Rol
                    $RolModel->save($Roldata);
                    $message = 'Rol creado correctamente.';
                }

                return redirect()->to('rol')->with('success', $message);
            }
        }

        return view('crear_rol', $data);
    }

    public function delete($PK_ID_ROL)
    {
        $RolModel = new RolModel();

        if($RolModel->find($PK_ID_ROL)['FECHA_BAJA'] === null){
            $RolModel->update($PK_ID_ROL, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/rol')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $RolModel->update($PK_ID_ROL, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/rol')->with('success', 'Categoría dada de alta correctamente.');
        }
    }
}