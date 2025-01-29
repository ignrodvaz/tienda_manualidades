<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model{

    protected $table = 'PEDIDO'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_PEDIDO'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_PEDIDO', 'FECHA_PEDIDO', 'DIRECCION_PEDIDO', 'TOTAL_PEDIDO', 'FECHA_BAJA', 'ESTADO', 'FK_ID_CLIENTE']; //Campos permitidos para insertar/actualizar

}