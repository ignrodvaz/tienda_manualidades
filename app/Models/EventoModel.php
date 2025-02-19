<?php

namespace App\Models;

use CodeIgniter\Model;

class EventoModel extends Model{

    protected $table = 'EVENTO'; //Nombre de la tabla

    protected $primaryKey = 'PK_ID_EVENTO'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_EVENTO', 'TITULO', 'FECHA_INICIO', 'FECHA_FINAL', 'DESCRIPCION_ES', 'DESCRIPCION_ENG', 'FECHA_BAJA']; //Campos permitidos para insertar/actualizar
}