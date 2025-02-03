<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model

{

    protected $table = 'CLIENTE';

    protected $primaryKey = 'PK_ID_CLIENTE';

    protected $allowedFields = ['NOMBRE', 'EMAIL', 'CONTRASENA', 'TELEFONO', 'DIRECCION', 'FECHA_REGISTRO', 'FECHA_BAJA', 'FK_ID_ROL'];

    public function findByEmail(string $email){
        return $this->where('EMAIL', $email)->first();
    }
}

