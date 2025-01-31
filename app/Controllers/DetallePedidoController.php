<?php

namespace App\Controllers;

use App\Models\DetallePedidoModel;

class DetallePedidoController extends BaseController
{
    public function index()
    {
        $DetallePedidoModel = new DetallePedidoModel();

        $cantidad = $this->request->getVar('CANTIDAD');
        $precio_unitario = $this->request->getVar('PRECIO_UNITARIO');
        $fk_id_pedido = $this->request->getVar('FK_ID_PEDIDO');
        $producto_nombre = $this->request->getVar('PRODUCTO_NOMBRE');
        $estado = $this->request->getGet('estado') ?? 'todas';



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
        }

        // Configuración de la paginación
        $perPage = 3; // Número de resultados por página
        $data['detalles'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $DetallePedidoModel->pager; // Pasar el objeto del paginador a la vista
        $data['cantidad'] = $cantidad; // Mantener el término de búsqueda en la vista
        $data['precio_unitario'] = $precio_unitario;
        $data['fk_id_pedido'] = $fk_id_pedido;
        $data['producto_nombre'] = $producto_nombre;
        $data['estado'] = $estado; // Mantener el estado en la vista

        return view('listado_detalle_pedido', $data); // Cargar la vista con los datos
    }

    public function saveDetallePedido($PK_ID_DETALLE = null)
    {
        $DetallePedidoModel = new DetallePedidoModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['detalle'] = $PK_ID_DETALLE ? $DetallePedidoModel->find($PK_ID_DETALLE) : null;

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'cantidad' => 'required',
                
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
        $DetallePedidoModel = new DetallePedidoModel();
        $DetallePedidoModel->update($PK_ID_DETALLE, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
        return redirect()->to('/detalle_pedido')->with('success', 'Categoría dada de baja correctamente.');
    }
}