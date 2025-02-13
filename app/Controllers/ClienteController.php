<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\RolModel;

class ClienteController extends BaseController
{
    public function index()
    {
        $ClienteModel = new ClienteModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $email = $this->request->getVar('EMAIL');
        $contrasena = $this->request->getVar('CONTRASENA');
        $telefono = $this->request->getVar('TELEFONO');
        $direccion = $this->request->getVar('DIRECCION');
        $fecha_registro = $this->request->getVar('FECHA_REGISTRO');
        $rol = $this->request->getVar('ROL');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = $this->request->getVar('perPage') ?: 10;



        $query = $ClienteModel->select('CLIENTE.*, ROL.NOMBRE AS ROL_NOMBRE')->join('ROL', 'ROL.PK_ID_ROL = CLIENTE.FK_ID_ROL', 'left');

        // Aplicar filtro por estado usando switch
        switch ($estado) {
            case 'altas':
                $query->where('CLIENTE.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('CLIENTE.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        // Aplicar filtro si se introduce un nombre
        if($name){
            $query->like('CLIENTE.NOMBRE', $name);
        }else if($email){
            $query->like('CLIENTE.EMAIL', $email);
        }else if($contrasena){
            $query->like('CLIENTE.CONTRASENA', $contrasena);
        }else if($telefono){
            $query->like('CLIENTE.TELEFONO', $telefono);
        }else if($direccion){
            $query->like('CLIENTE.DIRECCION', $direccion);
        }else if($fecha_registro){
            $query->like('CLIENTE.FECHA_REGISTRO', $fecha_registro);
        }else if($rol){
            $query->like('ROL.PK_ID_ROL', $rol);
        }

        // Configuración de la paginación
        $data['clientes'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $ClienteModel->pager; // Pasar el objeto del paginador a la vista
        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['email'] = $email;
        $data['contrasena'] = $contrasena;
        $data['telefono'] = $telefono;
        $data['direccion'] = $direccion;
        $data['fecha_registro'] = $fecha_registro;
        $data['rol'] = $rol;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista

        return view('listado_cliente', $data); // Cargar la vista con los datos
    }

    public function saveCliente($PK_ID_CLIENTE = null)
    {
        $ClienteModel = new ClienteModel();
        $RolModel = new RolModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['cliente'] = $PK_ID_CLIENTE ? $ClienteModel->find($PK_ID_CLIENTE) : null;
        $data['roles'] = $RolModel->findAll(); // Obtener todos los roles

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[100]',
                
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El campo Nombre es obligatorio.',
                    'min_length' => 'El Nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El Nombre no puede exceder los 100 caracteres.',
                ],
            ];

            if (!$this->validate($rules, $messages)) {
                // Si las validaciones fallan, devuelve los errores
                $data['validation'] = $this->validator;

            } else {
                // Preparar datos del formulario
                $clienteData = [
                    'NOMBRE' => $this->request->getPost('nombre'),
                    'EMAIL'=> $this->request->getPost('email'),
                    'TELEFONO'=> $this->request->getPost('telefono'),
                    'DIRECCION'=> $this->request->getPost('direccion'),
                    'FECHA_REGISTRO'=> $this->request->getPost('fecha_registro'),
                    'FK_ID_ROL'=> $this->request->getPost('rol'),
                ];

                if($this->request->getPost('contrasena')){
                    $clienteData['CONTRASENA'] = password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT);
                }

                if ($PK_ID_CLIENTE) {
                    // Actualizar cliente existente
                    $ClienteModel->update($PK_ID_CLIENTE, $clienteData);
                    $message = 'Cliente actualizado correctamente.';
                } else {
                    // Crear nuevo cliente
                    $ClienteModel->save($clienteData);
                    $message = 'Cliente creada correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('cliente')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('crear_cliente', $data);
    }

    public function delete($PK_ID_CLIENTE)
    {
        $ClienteModel = new ClienteModel();

        if($ClienteModel->find($PK_ID_CLIENTE)['FECHA_BAJA'] === null){
            $ClienteModel->update($PK_ID_CLIENTE, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/cliente')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $ClienteModel->update($PK_ID_CLIENTE, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/cliente')->with('success', 'Categoría dada de alta correctamente.');
        }
        
    }

}