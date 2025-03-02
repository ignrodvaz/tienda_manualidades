<?php

namespace App\Controllers;

use App\Models\DetallePedidoModel;

class DetallePedidoController extends BaseController
{
    protected $DetallePedidoModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->DetallePedidoModel = new DetallePedidoModel();
    }
    public function index()
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR'){
            return redirect()->to('acceso_restringido');
        }

        $DetallePedidoModel = new DetallePedidoModel();

        $pk_id_detalle = $this->request->getVar('PK_ID_DETALLE');
        $cantidad = $this->request->getVar('CANTIDAD');
        $precio_unitario = $this->request->getVar('PRECIO_UNITARIO');
        $fk_id_pedido = $this->request->getVar('FK_ID_PEDIDO');
        $producto_nombre = $this->request->getVar('PRODUCTO_NOMBRE');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        $order_columna = trim($this->request->getGet('order_columna') ?? 'CANTIDAD');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');


        $query = $DetallePedidoModel->select('DETALLE_PEDIDO.*, PRODUCTO.NOMBRE as PRODUCTO_NOMBRE')
        ->join('PEDIDO', 'DETALLE_PEDIDO.FK_ID_PEDIDO = PEDIDO.PK_ID_PEDIDO', 'left')
        ->join('PRODUCTO', 'DETALLE_PEDIDO.FK_ID_PRODUCTO = PRODUCTO.PK_ID_PRODUCTO', 'left');

        // Aplicar filtro por estado usando switch
        switch ($estado) {
            case 'altas':
                $query->where('DETALLE_PEDIDO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('DETALLE_PEDIDO.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        // Aplicar filtro si se introduce una cantidad
        if($cantidad){
            $query->like('DETALLE_PEDIDO.CANTIDAD', $cantidad);
        }else if($precio_unitario){
            $query->like('DETALLE_PEDIDO.PRECIO_UNITARIO', $precio_unitario);
        }else if($fk_id_pedido){
            $query->like('PEDIDO.PK_ID_PEDIDO', $fk_id_pedido);
        }else if($producto_nombre){
            $query->like('PRODUCTO.NOMBRE', $producto_nombre);
        }else if($pk_id_detalle){
            $query->like('DETALLE_PEDIDO.PK_ID_DETALLE', $pk_id_detalle);
        }

        $columnas_validas = ['PK_ID_DETALLE', 'CANTIDAD', 'PRECIO_UNITARIO', 'FK_ID_PEDIDO', 'PRODUCTO_NOMBRE'];
        if(in_array($order_columna, $columnas_validas) && in_array($order_direccion, ['asc', 'desc'])){
            $query->orderBy($order_columna, $order_direccion);
        }

        // Configuración de la paginación
        $data['detalles'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $DetallePedidoModel->pager; // Pasar el objeto del paginador a la vista
        $data['pk_id_detalle'] = $pk_id_detalle; // Mantener el término de búsqueda en la vista
        $data['cantidad'] = $cantidad; // Mantener el término de búsqueda en la vista
        $data['precio_unitario'] = $precio_unitario;
        $data['fk_id_pedido'] = $fk_id_pedido;
        $data['producto_nombre'] = $producto_nombre;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista
        $data['order_columna'] = $order_columna; // Mantener la columna de orden en la vista
        $data['order_direccion'] = $order_direccion; // Mantener la dirección de orden en la vista

        return view('listado_detalle_pedido', $data); // Cargar la vista con los datos
    }

    public function saveDetallePedido($PK_ID_DETALLE = null)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR'){
            return redirect()->to('acceso_restringido');
        }

        $DetallePedidoModel = new DetallePedidoModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['detalle'] = $PK_ID_DETALLE ? $DetallePedidoModel->find($PK_ID_DETALLE) : null;

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'cantidad' => 'required',
                'precio_unitario' => 'required',
            ];

            $messages = [
                'cantidad' => ['required' => 'El campo cantidad es obligatorio'],
                'precio_unitario' => ['required' => 'El campo precio unitario es obligatorio'],
            ];

            if (!$this->validate($rules, $messages)) {
                // Si las validaciones fallan, devuelve los errores
                $data['validation'] = $this->validator;

            } else {
                // Preparar datos del formulario
                $detallePedidoData = [
                    'CANTIDAD' => $this->request->getPost('cantidad'),
                    'PRECIO_UNITARIO'=> $this->request->getPost('precio_unitario'),
                ];

                if ($PK_ID_DETALLE) {
                    // Actualizar cliente existente
                    $DetallePedidoModel->update($PK_ID_DETALLE, $detallePedidoData);
                    $message = 'Cliente actualizado correctamente.';
                } else {
                    // Crear nuevo cliente
                    $DetallePedidoModel->save($detallePedidoData);
                    $message = 'Cliente creada correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('detalle_pedido')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('crear_detalle_pedido', $data);
    }

    public function delete($PK_ID_DETALLE)
    {
        $session = session();
        if(!$session->get('isLoggedIn')){
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR'){
            return redirect()->to('acceso_restringido');
        }
        
        $DetallePedidoModel = new DetallePedidoModel();

        if($DetallePedidoModel->find($PK_ID_DETALLE)['FECHA_BAJA'] === null){
            $DetallePedidoModel->update($PK_ID_DETALLE, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/detalle_pedido')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $DetallePedidoModel->update($PK_ID_DETALLE, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/detalle_pedido')->with('success', 'Categoría dada de alta correctamente.');
        }
    }

    public function exportar()
    {
        $pk_id_detalle = $this->request->getVar('PK_ID_DETALLE');
        $cantidad = $this->request->getVar('CANTIDAD');
        $precio_unitario = $this->request->getVar('PRECIO_UNITARIO');
        $fk_id_pedido = $this->request->getVar('FK_ID_PEDIDO');
        $producto_nombre = $this->request->getVar('PRODUCTO_NOMBRE');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $order_columna = $this->request->getGet('order_columna') ?? 'CANTIDAD';
        $order_direccion = $this->request->getGet('order_direccion') ?? 'asc';

        $DetallePedidoModel = new DetallePedidoModel();

        $query = $DetallePedidoModel->select('DETALLE_PEDIDO.*, PRODUCTO.NOMBRE as PRODUCTO_NOMBRE')
        ->join('PEDIDO', 'DETALLE_PEDIDO.FK_ID_PEDIDO = PEDIDO.PK_ID_PEDIDO', 'left')
        ->join('PRODUCTO', 'DETALLE_PEDIDO.FK_ID_PRODUCTO = PRODUCTO.PK_ID_PRODUCTO', 'left');
        
        switch($estado){
            case 'altas':
                $query->where('DETALLE_PEDIDO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('DETALLE_PEDIDO.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        if($pk_id_detalle){
            $query->like('DETALLE_PEDIDO.PK_ID_DETALLE', $pk_id_detalle);
        }else if($cantidad){
            $query->like('DETALLE_PEDIDO.CANTIDAD', $cantidad);
        }else if($precio_unitario){
            $query->like('DETALLE_PEDIDO.PRECIO_UNITARIO', $precio_unitario);
        }else if($fk_id_pedido){
            $query->like('PEDIDO.PK_ID_PEDIDO', $fk_id_pedido);
        }else if($producto_nombre){
            $query->like('PRODUCTO.NOMBRE', $producto_nombre);
        }

        $query = $query->orderBy($order_columna, $order_direccion);

        $detalles = $query->findAll();

        if(empty($detalles)){
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="detalles_pedido_filtradas.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['PK_ID_DETALLE', 'CANTIDAD', 'PRECIO_UNITARIO', 'FK_ID_PEDIDO', 'PRODUCTO_NOMBRE', 'FECHA_BAJA', 'Fecha de creación', 'Fecha de ultima modificación'], ';');

        foreach($detalles as $detalle){
            fputcsv($output, [$detalle['PK_ID_DETALLE'], $detalle['CANTIDAD'], $detalle['PRECIO_UNITARIO'], $detalle['FK_ID_PEDIDO'], $detalle['PRODUCTO_NOMBRE'], $detalle['FECHA_BAJA'], $detalle['created_at'], $detalle['updated_at']], ';');
        }

        fclose($output);
        exit();

    }

}