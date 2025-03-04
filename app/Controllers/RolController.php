<?php

namespace App\Controllers;

use App\Models\RolModel;

class RolController extends BaseController
{
    protected $RolModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->RolModel = new RolModel();
    }

    public function index()
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR'){
            return redirect()->to('acceso_restringido');
        }

        $RolModel = new RolModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $pk_id_rol = $this->request->getVar('PK_ID_ROL');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        $order_columna = $this->request->getGet('order_columna') ?? 'NOMBRE';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';

        $columnas_validas = ['NOMBRE', 'DESCRIPCION'];
        $direcciones_validas = ['asc', 'desc'];

        if (!in_array($order_columna, $columnas_validas)) {
            $order_columna = 'NOMBRE';
        }
        if (!in_array($order_direccion, $direcciones_validas)) {
            $order_direccion = 'asc';
        }

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

        $query = $query->orderBy($order_columna, $order_direccion);

        // Configuración de la paginación
        $data['roles'] = $query->paginate($perPage); // Obtener roles paginados
        $data['pager'] = $RolModel->pager; // Pasar el objeto del paginador a la vista


        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['descripcion'] = $descripcion;
        $data['pk_id_rol'] = $pk_id_rol;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista

        // Mantener ordenación
        $data['order_columna'] = $order_columna;
        $data['order_direccion'] = $order_direccion;

        return view('listado_rol', $data); // Cargar la vista con los datos
    }

    public function saveRol($PK_ID_ROL = null)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR'){
            return redirect()->to('acceso_restringido');
        }

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
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR'){
            return redirect()->to('acceso_restringido');
        }
        
        $RolModel = new RolModel();

        if($RolModel->find($PK_ID_ROL)['FECHA_BAJA'] === null){
            $RolModel->update($PK_ID_ROL, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/rol')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $RolModel->update($PK_ID_ROL, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/rol')->with('success', 'Categoría dada de alta correctamente.');
        }
    }

    public function exportar()
    {
        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $pk_id_rol = $this->request->getVar('PK_ID_ROL');
        $estado = $this->request->getGet('estado') ?? 'todas';

        $order_columna = $this->request->getGet('order_columna') ?? 'NOMBRE';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';

        $query = $this->RolModel;

        if (!empty($name)) {
            $query = $query->like('NOMBRE', $name);
        } else if (!empty($descripcion)) {
            $query = $query->like('DESCRIPCION', $descripcion);
        } else if (!empty($pk_id_rol)) {
            $query = $query->where('PK_ID_ROL', $pk_id_rol);
        }

        if ($estado === 'altas') {
            $query = $query->where('FECHA_BAJA', null);
        } else if ($estado === 'bajas') {
            $query = $query->where('FECHA_BAJA !=', null);
        }

        $query = $query->orderBy($order_columna, $order_direccion);

        $roles = $query->findAll();

        if(empty($roles)){
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="roles_filtrados.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'Nombre', 'Descripción', 'Fecha de baja'));

        foreach ($roles as $rol) {
            fputcsv($output, [$rol['PK_ID_ROL'], $rol['NOMBRE'], $rol['DESCRIPCION'], $rol['FECHA_BAJA']]);
        }

        fclose($output);
        exit(); 
    }
}