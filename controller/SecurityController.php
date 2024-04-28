<?php

namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\UserManager;
// on indique le namespace de la dépendance pour que la class PHPMailer soit trouvée
use PHPMailer\PHPMailer\PHPMailer;
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
                    "token" => $this->generateTokenUnique()
                ]);
                $_SESSION["success"] = "Votre inscription est terminée, veuillez vérifier vos email";
                $this->redirectTo("home", "index");
            } else {
                $_SESSION["error"] = "Nom d'utilisateur déjà utilisé";
                $this->redirectTo("home", "index");
            }
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
        return [
            "view" => VIEW_DIR . "security/login.php",
            "meta_description" => "Connectez-vous pour participer au Forum",
            "data" => []
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
    // nous renseignons les informations avec les arguments demandé
    public function sentEmailTo(string $to, string $subject, string $body)
    {
        // si on se trouve sur un serveur local on utilise phpmailer
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            $phpmail = new PHPMailer();
            // ici c'est la configuration su server de messagerie mailtrap
            $phpmail->isSMTP();
            $phpmail->Host = 'sandbox.smtp.mailtrap.io';
            $phpmail->SMTPAuth = true;
            $phpmail->Username = '1115a6ea5b4a74';
            $phpmail->Password = '4fa1184cc03b34';
            $phpmail->Port = 2525;
            // ici on immagine avoir une messagerie 
            $phpmail->setFrom('@gmail.com', 'Services Forum');
            // on indique a PHPMailer l'adresse de destination 
            $phpmail->addAddress($to, 'Recipient Name');
            // Sujet de l'e-mail
            $phpmail->Subject = $subject;
            // Contenu de l'e-mail 
            $phpmail->isHTML(true);
            // Indique que le contenu est au format HTML
            $phpmail->CharSet = 'UTF-8';
            // Définit l'encodage des caractères
            $phpmail->Body = $body;
            // on envois le mail et retournons la reponse de l'envois (true or false)
            return $phpmail->send();
        }
        //si on se trouve sur un serveur distant on utilise la fonction nativ de php mail()
        else {
            // En-têtes de l'email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: expéditeur@example.com' . "\r\n";
            $headers .= 'Reply-To: expéditeur@example.com' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            // on envois le mail et retournons la reponse de l'envois (true or false)
            return mail($to, $subject, $body, $headers);
        }
        return [
            "view" => VIEW_DIR . "home.php",
            "meta_description" => "Créer un compte pour participer au Forum",
            "data" => []
        ];
    }
}
