<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model{

    protected $table = 'CLIENTE'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_CLIENTE'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_CLIENTE', 'NOMBRE', 'EMAIL', 'CONTRASENA', 'TELEFONO', 'DIRECCION', 'FECHA_REGISTRO', 'FECHA_BAJA', 'FK_ID_ROL']; //Campos permitidos para insertar/actualizar

}