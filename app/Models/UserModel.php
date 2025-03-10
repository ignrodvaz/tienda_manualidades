<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model

{

    protected $table = 'CLIENTE';

    protected $primaryKey = 'PK_ID_CLIENTE';

    protected $allowedFields = ['NOMBRE', 'EMAIL', 'CONTRASENA', 'google_id', 'TELEFONO', 'DIRECCION', 'FECHA_REGISTRO', 'FECHA_BAJA', 'FK_ID_ROL'];

    public function findByEmail(string $email){
        return $this->where('EMAIL', $email)->first();
    }

    public function getUsuariosPorRol($rol)
    {
        return $this->select('PK_ID_CLIENTE, NOMBRE')
                    ->where('FK_ID_ROL', $rol)
                    ->findAll();
    }

}

