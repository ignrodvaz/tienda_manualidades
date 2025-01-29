<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model{

    protected $table = 'PRODUCTO'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_PRODUCTO'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_PRODUCTO', 'NOMBRE', 'DESCRIPCION', 'PRECIO', 'STOCK', 'FECHA_BAJA', 'FK_ID_CATEGORIA']; //Campos permitidos para insertar/actualizar

}