<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;

class ProductoController extends BaseController
{
    public function index()
    {
        $ProductoModel = new ProductoModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this->request->getVar('DESCRIPCION');
        $precio = $this->request->getVar('PRECIO');
        $stock = $this->request->getVar('STOCK');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $fk_id_categoria = $this->request->getVar('FK_ID_CATEGORIA');
        $estado = $this->request->getGet('estado') ?? 'todas';



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

        // Configuración de la paginación
        $perPage = 5; // Número de resultados por página
        $data['productos'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $ProductoModel->pager; // Pasar el objeto del paginador a la vista
        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['descripcion'] = $descripcion;
        $data['precio'] = $precio;
        $data['stock'] = $stock;
        $data['fk_id_categoria'] = $fk_id_categoria;
        $data['estado'] = $estado; // Mantener el estado en la vista

        return view('listado_producto', $data); // Cargar la vista con los datos
    }

    public function saveProducto($PK_ID_PRODUCTO = null)
    {
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
        $ProductoModel = new ProductoModel();
        $ProductoModel->update($PK_ID_PRODUCTO, ['FECHA_BAJA' => date('Y-m-d H:i:s')]); // Dar de baja la categoría
        return redirect()->to('/producto')->with('success', 'Categoría dada de baja correctamente.');
    }
}