<?php

namespace App\Controllers;

use Google\Client;
use Google\Service\Oauth2;
use App\Models\UserModel;
use Config\Google;

class GoogleAuth extends BaseController
{
    public function login()
    {
        $googleConfig = new Google(); // Aquí estamos usando la clase Google cargada desde Config

        $client = new Client();
        $client->setClientId($googleConfig->clientID);
        $client->setClientSecret($googleConfig->clientSecret);
        $client->setRedirectUri($googleConfig->redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        return redirect()->to($client->createAuthUrl());
    }

    public function callback()
    {
        $session = session();

        $googleConfig = new Google();

        $client = new Client();
        $client->setClientId($googleConfig->clientID);
        $client->setClientSecret($googleConfig->clientSecret);
        $client->setRedirectUri($googleConfig->redirectUri);

        if ($this->request->getGet('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($this->request->getGet('code'));
            $client->setAccessToken($token['access_token']);

            $oauth = new Oauth2($client);
            $userInfo = $oauth->userinfo->get();

            // Verificar si el usuario ya existe en la base de datos
            $userModel = new UserModel();
            $user = $userModel->where('EMAIL', $userInfo->email)->first();

            if (!$user) {
                // Registrar usuario si no existe
                $newUser = [
                    'NOMBRE'   => $userInfo->name,  // Usar 'NOMBRE' en lugar de 'name'
                    'EMAIL'    => $userInfo->email, // Usar 'EMAIL' en lugar de 'email'
                    'CONTRASENA' => '', // No guardamos contraseña
                    'PK_ID_CLIENTE' => $userInfo->id   // Si no tienes la columna google_id, cámbiala por 'google_id'
                ];

                $userModel->insert($newUser);
                $userId = $userModel->insertID(); // Obtiene el ID del último insert

                // Recuperar el usuario recién insertado
                $user = [
                    'id'    => $userId,  // Usar el ID correcto de la base de datos
                    'name'  => $userInfo->name,
                    'email' => $userInfo->email
                ];
            } else {
                // Asignar correctamente el ID desde la base de datos
                $user = [
                    'id'    => $user['PK_ID_CLIENTE'], // Asegúrate de usar la clave correcta de la base de datos
                    'name'  => $user['NOMBRE'],
                    'email' => $user['EMAIL']
                ];
            }

            // Iniciar sesión
            $session->set([
                'user_id'    => $user['id'],
                'user_email' => $user['email'],
                'user_name'  => $user['name'],
                'logged_in'  => true
            ]);

            return redirect()->to('/home');
        } else {
            return redirect()->to('/login')->with('error', 'Error al iniciar sesión con Google.');
        }
    }   


}
