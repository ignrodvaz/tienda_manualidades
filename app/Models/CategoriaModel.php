<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model{

    protected $table = 'CATEGORIA'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_CATEGORIA'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_CATEGORIA', 'NOMBRE', 'DESCRIPCION', 'FECHA_BAJA']; //Campos permitidos para insertar/actualizar

}