<?php

namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\UserManager;

use DateTime;

class SecurityController extends AbstractController
{
    // contiendra les méthodes liées à l'authentification : register, login et logout

    public function register()
    {
        // si $_POST on vérifie que toutes les clés existes et qu'elles sont bien remplis
        if (
            isset($_POST["username"]) && !empty($_POST["username"])
            && isset($_POST["email"]) && !empty($_POST["email"])
            && isset($_POST["password"]) && !empty($_POST["password"])
            && isset($_POST["repeat_password"]) && !empty($_POST["repeat_password"])
        ) {
            // on instancie UserManager
            $userManager = new UserManager();
            // on filtre toutes les entrées
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            // on vérifie si l'email existe dans la base de données table user
            $emailExist = $userManager->searchIfEmailExist($email);

            if ($emailExist) {
                // l'email existe donc l'user est déjà inscrit
                $_SESSION["success"] = "Déjà inscrit";
                $this->redirectTo("home", "index");
            }
            // à prévoir un regex de qualité
            //  Longueur entre 8 et 16 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial :
            //   `/(?=.[A-Z])(?=.[a-z])(?=.\d)(?=.[!@#$%^&*()_+}{:;'?/>,-])(?!.*\s).{8,16}/` 
            // on filtre les mots de passe
            $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/.{6,25}/")));
            $repeat_password = filter_input(INPUT_POST, 'repeat_password', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/.{6,25}/")));

            if (!$password || !$repeat_password) {
                // on affiche une alert a l'utilisateur
                $_SESSION["error"] = "Les mots ne respectent pas les critères de soumission";
                $this->redirectTo("home", "index");
            }
            if ($password !== $repeat_password) {
                // on affiche une alert a l'utilisateur
                $_SESSION["error"] = "Les mots de passe ne sont pas identique";
                $this->redirectTo("home", "index");
            }
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            /*$username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, array(
                "options" => array("regexp" => "/^[a-zA-Z0-9_]{3,25}$/")
            ));*/
            $usernameExist = $userManager->searchIfUsernamelExist($username);
            $token = $this->generateTokenUnique();

            if (!$usernameExist) {
                $date = new DateTime("now");
                $userManager->add([
                    "username" => $username,
                    "password" => $this->generatePasswordHash($repeat_password),
                    "email" => $email,
                    "dateRegister" => $date->format('Y-m-d H:i:s'),
                    "role" => json_encode([
                        "ROLE_VISITOR" // l'insciption n'étant pas fini il reste visiteur
                    ]),
                    "token" => $token
                ]);
                $subject = "Merci pour votre inscription au Forum@";
                $content = "<p>Veuillez confirmer votre inscription en cliquant sur le lien suivant:</p>
                <a href='http://localhost/forum/index.php?ctrl=security&action=login&token=" . $token . "'>Cliquez ici</a>";
                // <a href='".BASE_DIR."?ctrl=user?action=login?token=" .$token. "'>Cliquez ici</a>";
                $result = $this->sentEmailTo($email, $subject, $content);

                $result ? $_SESSION["success"] = "Votre inscription est terminée, veuillez vérifier vos email"
                    : $_SESSION["error"] = "Une erreur est survenue lors de l'envois de la confirmation par Email";
            } else $_SESSION["error"] = "Nom d'utilisateur déjà utilisé";

            $this->redirectTo("home", "index");
        } else {
            return [
                "view" => VIEW_DIR . "security/loginSignin.php",
                "meta_description" => "Créer un compte pour participer au Forum",
                "data" => []
            ];
        }
    }
    public function login()
    {
        // si la requête provient d'une inscription et validation par email
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            // on filtre le token bin2hex {32} caractères
            $token = filter_input(INPUT_GET, 'token', FILTER_VALIDATE_REGEXP, array(
                "options" => array("regexp" => "/^[a-f0-9]{64}$/")
            ));
            // on instancie UserManager
            $userManager = new UserManager();
            // si l'on trouve le token en bdd on le vide 
            // et retournons l'id_user
            $id_user = $userManager->searchIfTokenlExist($token);
            // si le token vaux false la confirmation a déjà eu lieu 
            // ou le token est erroné
            if(!$token) {
                $_SESSION["error"] = "L'inscription à déjà été confirmer";
                $this->redirectTo("home", "index");
            }
            // On modifie le role de visiteur à user
            $role = ["ROLE_USER"];
            $userManager->updateRoleUser(json_encode($role), $id_user);
            $_SESSION["success"] = "Votre inscription a bien été validé";
            $this->redirectTo("home", "index");

        } else {
            return [
                "view" => VIEW_DIR . "security/login.php",
                "meta_description" => "Connectez-vous pour participer au Forum",
                "data" => []
            ];
        }
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
