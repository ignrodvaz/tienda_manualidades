<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class CategoriaController extends BaseController
{

    protected $categoriaModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->categoriaModel = new CategoriaModel();
    }
    
    public function index()
    {   
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR'){
            return redirect()->to('acceso_restringido');
        }

        $CategoriaModel = new CategoriaModel();

        // Obtener parámetros de búsqueda, filtrado y ordenación
        $name = $this->request->getGet('NOMBRE');
        $descripcion = $this->request->getGet('DESCRIPCION');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        // Parámetros de ordenación
        $order_columna = $this->request->getGet('order_columna') ?? 'NOMBRE';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';

        // Validar valores permitidos
        $columnas_validas = ['NOMBRE', 'DESCRIPCION'];
        $direcciones_validas = ['asc', 'desc'];

        if (!in_array($order_columna, $columnas_validas)) {
            $order_columna = 'NOMBRE';
        }
        if (!in_array($order_direccion, $direcciones_validas)) {
            $order_direccion = 'asc';
        }

        // Construcción de la consulta
        $query = $CategoriaModel->select('*')->orderBy($order_columna, $order_direccion);

        if ($estado === 'altas') {
            $query = $query->where('FECHA_BAJA', null);
        } elseif ($estado === 'bajas') {
            $query = $query->where('FECHA_BAJA !=', null);
        }

        if ($name) {
            $query = $query->like('NOMBRE', $name);
        }
        if ($descripcion) {
            $query = $query->like('DESCRIPCION', $descripcion);
        }

        // Paginación manteniendo parámetros
        $data['categorias'] = $query->paginate($perPage);
        $data['pager'] = $CategoriaModel->pager;

        // Mantener los filtros y la paginación en la vista
        $data['name'] = $name;
        $data['descripcion'] = $descripcion;
        $data['estado'] = $estado;
        $data['perPage'] = $perPage;

        // Mantener ordenación
        $data['order_columna'] = $order_columna;
        $data['order_direccion'] = $order_direccion;

        return view('listado_categoria', $data);
    }



    public function saveCategoria($PK_ID_CATEGORIA = null)
    {   
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');

        if($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR'){
            return redirect()->to('acceso_restringido');
        }

        $CategoriaModel = new CategoriaModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['categoria'] = $PK_ID_CATEGORIA ? $CategoriaModel->find($PK_ID_CATEGORIA) : null;

        // Verificar el rol del usuario
        $session = session();
        $rol = $session->get('rol');
        if ($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR') {
            return redirect()->to('acceso_restringido');
        }

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[100]',
                'descripcion' => 'required|min_length[3]|max_length[100]',
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El campo Nombre es obligatorio.',
                    'min_length' => 'El Nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El Nombre no puede exceder los 100 caracteres.',
                ],
                'descripcion' => [
                    'required' => 'El campo Descripción es obligatorio.',
                ],
            ];

            if (!$this->validate($rules, $messages)) {
                // Si las validaciones fallan, devuelve los errores
                $data['validation'] = $this->validator;
            } else {
                // Preparar datos del formulario
                $categoriaData = [
                    'NOMBRE' => $this->request->getPost('nombre'),
                    'DESCRIPCION' => $this->request->getPost('descripcion'),
                ];

                if ($PK_ID_CATEGORIA) {
                    // Actualizar categoría existente
                    $CategoriaModel->update($PK_ID_CATEGORIA, $categoriaData);
                    $message = 'Categoría actualizada correctamente.';
                } else {
                    // Crear nueva categoría
                    $CategoriaModel->save($categoriaData);
                    $message = 'Categoría creada correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('categoria')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('crear_categoria', $data);
    }

    public function delete($PK_ID_CATEGORIA)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR'){
            return redirect()->to('acceso_restringido');
        }

        $CategoriaModel = new CategoriaModel();

        if($CategoriaModel->find($PK_ID_CATEGORIA)['FECHA_BAJA'] === null){
            $CategoriaModel->update($PK_ID_CATEGORIA, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/categoria')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $CategoriaModel->update($PK_ID_CATEGORIA, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/categoria')->with('success', 'Categoría dada de alta correctamente.');
        }
        
    }

    public function exportar()
    {
        $nombre = $this->request->getGet('NOMBRE');
        $descripcion = $this->request->getGet('DESCRIPCION');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $order_columna = $this->request->getGet('order_columna') ?? 'NOMBRE';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';


        $query = $this->categoriaModel;
        
        if (!empty($nombre)) {
            $query = $query->like('NOMBRE', $nombre);
        }

        if (!empty($descripcion)) {
            $query = $query->like('DESCRIPCION', $descripcion);
        }

        if ($estado === 'altas') {
            $query = $query->where('FECHA_BAJA', null);
        } elseif ($estado === 'bajas') {
            $query = $query->where('FECHA_BAJA !=', null);
        }

        $query = $query->orderBy($order_columna, $order_direccion);

        $categorias = $query->findAll();

        if (empty($categorias)) {
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="categorias_filtradas.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Descripción', 'Fecha de Baja', 'Fecha de Creacion', 'Fecha ultima modificación'], ';');

        foreach ($categorias as $categoria) {
            fputcsv($output, [$categoria['PK_ID_CATEGORIA'], $categoria['NOMBRE'], $categoria['DESCRIPCION'],$categoria['FECHA_BAJA'], $categoria['created_at'], $categoria['updated_at']], ';');
        }

        fclose($output);
        exit;
    }



}