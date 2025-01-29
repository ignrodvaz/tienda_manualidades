<?php

namespace App\Models;

use CodeIgniter\Model;

class DetallePedidoModel extends Model{

    protected $table = 'DETALLE_PEDIDO'; //Nombre de la tabla
    protected $primaryKey = 'PK_ID_DETALLE'; //Clave Primaria

    protected $useTimestamps = true; //Habilitamos el uso de las marcas de tiempo automaticas (create_at, update_at).

    protected $allowedFields = ['PK_ID_DETALLE', 'CANTIDAD', 'PRECIO_UNITARIO', 'FECHA_BAJA', 'FK_ID_PEDIDO', 'FK_ID_PRODUCTO']; //Campos permitidos para insertar/actualizar

}