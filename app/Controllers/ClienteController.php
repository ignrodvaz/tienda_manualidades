<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\RolModel;

class ClienteController extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $rolUsuario = $session->get('rol');
        if ($rolUsuario !== 'ADMINISTRADOR') {
            return redirect()->to('acceso_restringido');
        }

        $ClienteModel = new ClienteModel();

        // Obtener los filtros del request
        $name = trim($this->request->getVar('NOMBRE') ?? '');
        $email = trim($this->request->getVar('EMAIL') ?? '');
        $contrasena = trim($this->request->getVar('CONTRASENA') ?? '');
        $telefono = trim($this->request->getVar('TELEFONO') ?? '');
        $direccion = trim($this->request->getVar('DIRECCION') ?? '');
        $fecha_registro = trim($this->request->getVar('FECHA_REGISTRO') ?? '');
        $rol = trim($this->request->getVar('ROL') ?? '');
        $estado = strtolower(trim($this->request->getGet('estado') ?? 'todas'));
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        $order_columna = trim($this->request->getGet('order_columna') ?? 'NOMBRE');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');

        // Construcción de la consulta
        $query = $ClienteModel
            ->select('CLIENTE.*, ROL.NOMBRE AS ROL_NOMBRE')
            ->join('ROL', 'ROL.PK_ID_ROL = CLIENTE.FK_ID_ROL', 'left');

        // Aplicar filtro por estado
        switch ($estado) {
            case 'altas':
                $query->where('CLIENTE.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('CLIENTE.FECHA_BAJA IS NOT NULL');
                break;
        }

        // Aplicar filtros de búsqueda si existen
        if (!empty($name)) {
            $query->like('CLIENTE.NOMBRE', $name);
        }
        if (!empty($email)) {
            $query->like('CLIENTE.EMAIL', $email);
        }
        if (!empty($contrasena)) {
            $query->like('CLIENTE.CONTRASENA', $contrasena);
        }
        if (!empty($telefono)) {
            $query->like('CLIENTE.TELEFONO', $telefono);
        }
        if (!empty($direccion)) {
            $query->like('CLIENTE.DIRECCION', $direccion);
        }
        if (!empty($fecha_registro)) {
            $query->like('CLIENTE.FECHA_REGISTRO', $fecha_registro);
        }
        if (!empty($rol)) {
            // Intentar determinar si es un ID o un nombre
            if (is_numeric($rol)) {
                $query->where('ROL.PK_ID_ROL', $rol);
            } else {
                $query->where('ROL.NOMBRE', $rol);
            }
        }

        // Aplicar ordenación
        $columnas_validas = ['NOMBRE', 'EMAIL', 'TELEFONO', 'DIRECCION', 'FECHA_REGISTRO', 'ROL_NOMBRE'];
        if (in_array($order_columna, $columnas_validas) && in_array($order_direccion, ['asc', 'desc'])) {
            $query->orderBy($order_columna, $order_direccion);
        }

        // Configuración de la paginación
        $data = [
            'clientes' => $query->paginate($perPage),
            'pager' => $ClienteModel->pager,
            'name' => $name,
            'email' => $email,
            'contrasena' => $contrasena,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'fecha_registro' => $fecha_registro,
            'rol' => $rol,
            'estado' => $estado,
            'perPage' => $perPage,
            'order_columna' => $order_columna,
            'order_direccion' => $order_direccion
        ];

        return view('listado_cliente', $data);
    }



    public function saveCliente($PK_ID_CLIENTE = null)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR'){
            return redirect()->to('acceso_restringido');
        }

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
                'email' => 'required|min_length[3]|max_length[100]',
                'telefono' => 'required|min_length[9]|max_length[9]',
                'direccion' => 'required|min_length[3]|max_length[100]',
                'rol' => 'required',
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El campo Nombre es obligatorio.',
                    'min_length' => 'El Nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El Nombre no puede exceder los 100 caracteres.',
                ],
                'email' => [
                    'required' => 'El campo Email es obligatorio.',
                    'min_length' => 'El Email debe tener al menos 3 caracteres.',
                    'max_length' => 'El Email no puede exceder los 100 caracteres.',
                ],
                'telefono' => [
                    'required' => 'El campo Teléfono es obligatorio.',
                    'min_length' => 'El Teléfono debe tener 9 caracteres.',
                    'max_length' => 'El Teléfono debe tener 9 caracteres.',
                ],
                'direccion' => [
                    'required' => 'El campo Dirección es obligatorio.',
                    'min_length' => 'La Dirección debe tener al menos 3 caracteres.',
                    'max_length' => 'La Dirección no puede exceder los 100 caracteres.',
                ],
                'rol' => [
                    'required' => 'El campo Rol es obligatorio.',
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
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR'){
            return redirect()->to('acceso_restringido');
        }
        
        $ClienteModel = new ClienteModel();

        if($ClienteModel->find($PK_ID_CLIENTE)['FECHA_BAJA'] === null){
            $ClienteModel->update($PK_ID_CLIENTE, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/cliente')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $ClienteModel->update($PK_ID_CLIENTE, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/cliente')->with('success', 'Categoría dada de alta correctamente.');
        }
        
    }

    public function exportar()
    {
        $nombre = $this->request->getGet('NOMBRE');
        $email = $this->request->getGet('EMAIL');
        $telefono = $this->request->getGet('TELEFONO');
        $direccion = $this->request->getGet('DIRECCION');
        $fecha_registro = $this->request->getGet('FECHA_REGISTRO');
        $rol = $this->request->getGet('ROL');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $order_columna = $this->request->getGet('order_columna') ?? 'NOMBRE';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';

        $ClienteModel = new ClienteModel();

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
        if($nombre){
            $query->like('CLIENTE.NOMBRE', $nombre);
        }else if($email){
            $query->like('CLIENTE.EMAIL', $email);
        }else if($telefono){
            $query->like('CLIENTE.TELEFONO', $telefono);
        }else if($direccion){
            $query->like('CLIENTE.DIRECCION', $direccion);
        }else if($fecha_registro){
            $query->like('CLIENTE.FECHA_REGISTRO', $fecha_registro);
        }else if($rol){
            $query->like('ROL.PK_ID_ROL', $rol);
        }

        $query = $query->orderBy($order_columna, $order_direccion);

        $clientes = $query->findAll();

        if (empty($clientes)) {
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="clientes_filtradas.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Email', 'Teléfono', 'Dirección', 'Fecha de Registro', 'Rol', 'Fecha de Baja', 'Fecha de Creacion', 'Fecha ultima modificación'], ';');

        foreach($clientes as $cliente){
            fputcsv($output, [$cliente['PK_ID_CLIENTE'], $cliente['NOMBRE'], $cliente['EMAIL'], $cliente['TELEFONO'], $cliente['DIRECCION'], $cliente['FECHA_REGISTRO'], $cliente['ROL_NOMBRE'], $cliente['FECHA_BAJA'], $cliente['created_at'], $cliente['updated_at']], ';');
        }

        fclose($output);
        exit;
    }



}