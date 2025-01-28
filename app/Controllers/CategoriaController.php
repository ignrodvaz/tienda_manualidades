<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class CategoriaController extends BaseController
{
    public function index()
    {
        $CategoriaModel = new CategoriaModel();

        $name = $this->request->getVar('NOMBRE'); // Obtener el término de búsqueda desde el formulario
        $descripcion = $this -> request -> getVar('DESCRIPCION');



        $query = $CategoriaModel;
        // Aplicar filtro si se introduce un nombre
        if($name){
            $query = $query->like('NOMBRE', $name);
        }else if($descripcion){
            $query = $query->like('DESCRIPCION', $descripcion);
        }

        // Configuración de la paginación
        $perPage = 3; // Número de resultados por página
        $data['categorias'] = $query->paginate($perPage); // Obtener categorías paginadas
        $data['pager'] = $CategoriaModel->pager; // Pasar el objeto del paginador a la vista
        $data['name'] = $name; // Mantener el término de búsqueda en la vista
        $data['descripcion'] = $descripcion;
        return view('listado_categoria', $data); // Cargar la vista con los datos
    }

    public function saveCategoria($PK_ID_CATEGORIA = null)
    {
        $CategoriaModel = new CategoriaModel();
        helper(['form', 'url']);

        // Cargar datos de la categoría si es edición
        $data['categoria'] = $PK_ID_CATEGORIA ? $CategoriaModel->find($PK_ID_CATEGORIA) : null;

        if ($this->request->getMethod()=='POST') {
            // Reglas de validación
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[100]',
                'descripcion' => 'required',
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
        $CategoriaModel = new CategoriaModel();
        $CategoriaModel->delete($PK_ID_CATEGORIA); // Eliminar categoría
        return redirect()->to('/categoria')->with('success', 'Categoría eliminada correctamente.');
    }
}