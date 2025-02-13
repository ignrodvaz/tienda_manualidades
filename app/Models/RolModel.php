<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model{

    protected $table = 'ROL'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_ROL'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_ROL', 'NOMBRE', 'DESCRIPCION', 'FECHA_BAJA', 'created_at']; //Campos permitidos para insertar/actualizar

    //Metodo para obtener el nombre del rol basado en el ID del rol
    public function getRolName($id){
        return $this->select('NOMBRE')->where('PK_ID_ROL', $id)->first();
    }

}