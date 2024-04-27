<?php

namespace Controller;

use App\AbstractController;
use App\ControllerInterface;

class SecurityController extends AbstractController
{
    // contiendra les méthodes liées à l'authentification : register, login et logout

    public function register()
    {
        return [
            "view" => VIEW_DIR . "security/loginSignin.php",
            "meta_description" => "Créer un compte pour participer au Forum",
            "data" => [
                "register" => true
            ]
        ];
    }
    public function login()
    {
        return [
            "view" => VIEW_DIR . "security/loginSignin.php",
            "meta_description" => "Connectez-vous pour participer au Forum",
            "data" => [
                "login" => true
            ]
        ];
    }
    public function logout()
    {
        return [
            "view" => VIEW_DIR . "security/loginSignin.php",
            "meta_description" => "Page de dé--connexion au Forum",
            "data" => []
        ];
    }
}
