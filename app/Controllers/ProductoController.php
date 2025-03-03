<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;

class ProductoController extends BaseController
{
    protected $productoModel;
    public function __construct()
    {
        helper(['form', 'url']);
        $this->productoModel = new ProductoModel();
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

        $ProductoModel = new ProductoModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $precio = $this->request->getVar('PRECIO');
        $stock = $this->request->getVar('STOCK');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $fk_id_categoria = $this->request->getVar('FK_ID_CATEGORIA');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        $order_columna = trim($this->request->getGet('order_columna') ?? 'NOMBRE');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');


        $query = $ProductoModel->select('PRODUCTO.*, CATEGORIA.NOMBRE AS CATEGORIA_NOMBRE')->join('CATEGORIA', 'CATEGORIA.PK_ID_CATEGORIA = PRODUCTO.FK_ID_CATEGORIA', 'left');

        // Aplicar filtro por estado usando switch
        switch ($estado) {
            case 'altas':
                $query->where('PRODUCTO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('PRODUCTO.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        // Aplicar filtro si se introduce un nombre
        if($name){
            $query->like('PRODUCTO.NOMBRE', $name);
        }else if($descripcion){
            $query->like('PRODUCTO.DESCRIPCION', $descripcion);
        }else if($precio){
            $query->like('PRODUCTO.PRECIO', $precio);
        }else if($stock){
            $query->like('PRODUCTO.STOCK', $stock);
        }else if($fk_id_categoria){
            $query->like('CATEGORIA.NOMBRE', $fk_id_categoria);
        }

        $columnas_validas = ['NOMBRE', 'DESCRIPCION', 'PRECIO', 'STOCK', 'CATEGORIA_NOMBRE'];
        if(in_array($order_columna, $columnas_validas) && in_array($order_direccion, ['asc', 'desc'])){
            $query->orderBy($order_columna, $order_direccion);
        }

        // Configuración de la paginación
        $data['productos'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $ProductoModel->pager; // Pasar el objeto del paginador a la vista
        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['descripcion'] = $descripcion;
        $data['precio'] = $precio;
        $data['stock'] = $stock;
        $data['fk_id_categoria'] = $fk_id_categoria;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista
        $data['order_columna'] = $order_columna; // Mantener la columna de orden en la vista
        $data['order_direccion'] = $order_direccion; // Mantener la dirección de orden en la vista

        return view('listado_producto', $data); // Cargar la vista con los datos
    }

    public function saveProducto($PK_ID_PRODUCTO = null)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR'){
            return redirect()->to('acceso_restringido');
        }

        $ProductoModel = new ProductoModel();
        $CategoriaModel = new CategoriaModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['producto'] = $PK_ID_PRODUCTO ? $ProductoModel->find($PK_ID_PRODUCTO) : null;
        $data['categorias'] = $CategoriaModel->findAll(); // Obtener todas las categorías

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[100]',
                'descripcion' => 'required|min_length[3]|max_length[100]',
                'precio' => 'required',
                'stock' => 'required',
                'fk_id_categoria' => 'required',
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El campo nombre es obligatorio.',
                    'min_length' => 'El campo nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El campo nombre no debe superar los 100 caracteres.',
                ],
                'descripcion' => [
                    'required' => 'El campo descripción es obligatorio.',
                    'min_length' => 'El campo descripción debe tener al menos 3 caracteres.',
                    'max_length' => 'El campo descripción no debe superar los 100 caracteres.',
                ],
                'precio' => [
                    'required' => 'El campo precio es obligatorio.',
                ],
                'stock' => [
                    'required' => 'El campo stock es obligatorio.',
                ],
                'fk_id_categoria' => [
                    'required' => 'El campo categoría es obligatorio.',
                ],
            ];

            if (!$this->validate($rules, $messages)) {
                // Si las validaciones fallan, devuelve los errores
                $data['validation'] = $this->validator;

            } else {
                // Preparar datos del formulario
                $productoData = [
                    'NOMBRE' => $this->request->getPost('nombre'),
                    'DESCRIPCION'=> $this->request->getPost('descripcion'),
                    'PRECIO'=> $this->request->getPost('precio'),
                    'STOCK'=> $this->request->getPost('stock'),
                    'FECHA_BAJA'=> $this->request->getPost('fecha_baja'),
                    'FK_ID_CATEGORIA'=> $this->request->getPost('fk_id_categoria'),
                ];

                if ($PK_ID_PRODUCTO) {
                    // Actualizar cliente existente
                    $ProductoModel->update($PK_ID_PRODUCTO, $productoData);
                    $message = 'Producto actualizado correctamente.';
                } else {
                    // Crear nuevo cliente
                    $ProductoModel->save($productoData);
                    $message = 'Producto creado correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('producto')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('crear_producto', $data);
    }

    public function delete($PK_ID_PRODUCTO)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'MODERADOR'){
            return redirect()->to('acceso_restringido');
        }
        
        $ProductoModel = new ProductoModel();

        if($ProductoModel->find($PK_ID_PRODUCTO)['FECHA_BAJA'] === null){
            $ProductoModel->update($PK_ID_PRODUCTO, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/producto')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $ProductoModel->update($PK_ID_PRODUCTO, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/producto')->with('success', 'Categoría dada de alta correctamente.');
        }
        
    }

    public function exportar()
    {
        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $precio = $this->request->getVar('PRECIO');
        $stock = $this->request->getVar('STOCK');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $fk_id_categoria = $this->request->getVar('FK_ID_CATEGORIA');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $order_columna = trim($this->request->getGet('order_columna') ?? 'NOMBRE');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');

        $ProductoModel = new ProductoModel();

        $query = $ProductoModel->select('PRODUCTO.*, CATEGORIA.NOMBRE AS CATEGORIA_NOMBRE')->join('CATEGORIA', 'CATEGORIA.PK_ID_CATEGORIA = PRODUCTO.FK_ID_CATEGORIA', 'left');

        // Aplicar filtro por estado usando switch
        switch ($estado) {
            case 'altas':
                $query->where('PRODUCTO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('PRODUCTO.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        // Aplicar filtro si se introduce un nombre
        if($name){
            $query->like('PRODUCTO.NOMBRE', $name);
        }else if($descripcion){
            $query->like('PRODUCTO.DESCRIPCION', $descripcion);
        }else if($precio){
            $query->like('PRODUCTO.PRECIO', $precio);
        }else if($stock){
            $query->like('PRODUCTO.STOCK', $stock);
        }else if($fk_id_categoria){
            $query->like('CATEGORIA.NOMBRE', $fk_id_categoria);
        }

        $query = $query->orderBy('PRODUCTO.'.$order_columna, $order_direccion);

        $productos = $query->findAll();

        if(empty($productos)){
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="producto_filtradas.csv"');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['Nombre', 'Descripción', 'Precio', 'Stock', 'Fecha de Baja', 'Nombre Categoria', 'Fecha de Creación', 'Fecha de Actualización'], ';');

        foreach ($productos as $producto) {
            fputcsv($output, [$producto['NOMBRE'], $producto['DESCRIPCION'], $producto['PRECIO'], $producto['STOCK'], $producto['FECHA_BAJA'], $producto['CATEGORIA_NOMBRE'], $producto['created_at'], $producto['updated_at']], ';');
        }

        fclose($output);
        exit();
    }
}