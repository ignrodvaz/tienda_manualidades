<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientID = 'ID cliente';
    public $clientSecret = 'ID Secreto';
    public $redirectUri = 'http://localhost:8888/tienda_manualidades/public/google-callback';
}
