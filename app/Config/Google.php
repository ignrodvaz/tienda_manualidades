<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientID = '766124376823-faak2m3edr07f3visjsugc04e1l9is4v.apps.googleusercontent.com';
    public $clientSecret = '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';
    public $redirectUri = 'http://localhost:8888/tienda_manualidades/public/google-callback';
}
