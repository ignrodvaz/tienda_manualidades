<?php

namespace App\Controllers;

use App\Models\PedidoModel;

class PedidoController extends BaseController
{
    public function index()
    {
        $PedidoModel = new PedidoModel();

        $fecha_pedido = $this->request->getVar('FECHA_PEDIDO');
        $direccion_pedido = $this->request->getVar('DIRECCION_PEDIDO');
        $total_pedido = $this->request->getVar('TOTAL_PEDIDO');
        $estado_pedido = $this->request->getVar('ESTADO');
        $fk_id_cliente = $this->request->getVar('FK_ID_CLIENTE');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = $this->request->getVar('perPage') ?: 10;



        $query = $PedidoModel->select('PEDIDO.*, CLIENTE.NOMBRE AS CLIENTE_NOMBRE')->join('CLIENTE', 'CLIENTE.PK_ID_CLIENTE = PEDIDO.FK_ID_CLIENTE', 'left');

        // Aplicar filtro por estado usando switch
        switch ($estado) {
            case 'altas':
                $query->where('PEDIDO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('PEDIDO.FECHA_BAJA !=', null);
                break;
            default:
                // No se aplica ningún filtro adicional para 'todas'
                break;
        }

        // Aplicar filtro si se introduce una cantidad
        if($fecha_pedido){
            $query->like('PEDIDO.FECHA_PEDIDO', $fecha_pedido);
        }else if($direccion_pedido){
            $query->like('PEDIDO.DIRECCION_PEDIDO', $direccion_pedido);
        }else if($total_pedido){
            $query->like('PEDIDO.TOTAL_PEDIDO', $total_pedido);
        }else if($estado_pedido){
            $query->like('PEDIDO.ESTADO', $estado_pedido);
        }else if($fk_id_cliente){
            $query->like('CLIENTE_NOMBRE', $fk_id_cliente);
        }

        // Configuración de la paginación
        $data['pedidos'] = $query->paginate($perPage); // Obtener categorías paginadas

         // Formatear fecha para mostrar solo YYYY-MM-DD
        foreach ($data['pedidos'] as &$pedido) {
            $pedido['FECHA_PEDIDO'] = date('Y-m-d', strtotime($pedido['FECHA_PEDIDO']));
        }

        $data['pager'] = $PedidoModel->pager; // Pasar el objeto del paginador a la vista
        $data['fecha_pedido'] = $fecha_pedido; // Mantener el término de búsqueda en la vista
        $data['direccion_pedido'] = $direccion_pedido;
        $data['total_pedido'] = $total_pedido;
        $data['estado_pedido'] = $estado_pedido;
        $data['fk_id_cliente'] = $fk_id_cliente;
        $data['estado'] = $estado; // Mantener el estado en la vista
        $data['perPage'] = $perPage; // Mantener el número de resultados por página en la vista

        return view('listado_pedido', $data); // Cargar la vista con los datos
    }

    public function savePedido($PK_ID_PEDIDO = null)
    {
        $PedidoModel = new PedidoModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['pedido'] = $PK_ID_PEDIDO ? $PedidoModel->find($PK_ID_PEDIDO) : null;
        

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'fecha_pedido' => 'required'
            ];

            $messages = [
                'cantidad' => [
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
                $PedidoData = [
                    'FECHA_PEDIDO' => $this->request->getPost('fecha_pedido'),
                    'DIRECCION_PEDIDO'=> $this->request->getPost('direccion_pedido'),
                    'TOTAL_PEDIDO'=> $this->request->getPost('total_pedido'),
                    'ESTADO'=> $this->request->getPost('estado_pedido'),
                    'FK_ID_CLIENTE'=> $this->request->getPost('fk_id_cliente'),
                ];

                if ($PK_ID_PEDIDO) {
                    // Actualizar cliente existente
                    $PedidoModel->update($PK_ID_PEDIDO, $PedidoData);
                    $message = 'Pedido actualizado correctamente.';
                } else {
                    // Crear nuevo cliente
                    $PedidoModel->save($PedidoData);
                    $message = 'Pedido creado correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('pedido')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('crear_pedido', $data);
    }

    public function delete($PK_ID_PEDIDO)
    {
        $PedidoModel = new PedidoModel();

        if($PedidoModel->find($PK_ID_PEDIDO)['FECHA_BAJA'] === null){
            $PedidoModel->update($PK_ID_PEDIDO, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
            return redirect()->to('/pedido')->with('success', 'Categoría dada de baja correctamente.');
        }else{
            $PedidoModel->update($PK_ID_PEDIDO, ['FECHA_BAJA' => NULL]); // Dar de alta la categoría
            return redirect()->to('/pedido')->with('success', 'Categoría dada de alta correctamente.');
        }
        
    }
}