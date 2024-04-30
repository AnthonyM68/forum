<?php

namespace App;
// on indique le namespace de la dépendance pour que la class PHPMailer soit trouvée
use PHPMailer\PHPMailer\PHPMailer;
/*
    En programmation orientée objet, une classe abstraite est une classe qui ne peut pas être instanciée directement. Cela signifie que vous ne pouvez pas créer un objet directement à partir d'une classe abstraite.
    Les classes abstraites : 
    -- peuvent contenir à la fois des méthodes abstraites (méthodes sans implémentation) et des méthodes concrètes (méthodes avec implémentation).
    -- peuvent avoir des propriétés (variables) avec des valeurs par défaut.
    -- une classe peut étendre une seule classe abstraite.
    -- permettent de fournir une certaine implémentation de base.
*/

abstract class AbstractController
{

    public function index()
    {
    }

    public function redirectTo($ctrl = null, $action = null, $id = null)
    {
        $url = $ctrl ? "?ctrl=" . $ctrl : "";
        $url .= $action ? "&action=" . $action : "";
        $url .= $id ? "&id=" . $id : "";

        header("Location: $url");
        die();
    }

    public function restrictTo($role)
    {
        // s'il n'y a pas de session de démarrer
        if (!Session::getUser() || !Session::getUser()->hasRole($role)) {
            $this->redirectTo("security", "login");
        }
        return;
    }
    public function generateTokenUnique()
    {
        $length = 32;
        // méthode pour générer un jeton unique
        return  bin2hex(random_bytes($length));
    }
    // méthode pour hasher un mot de passe
    public function generatePasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    // méthode pour vérifer si un hash récupéré dans la base de données correspond
    // au mot de pass clair saisie par l'utilisateur lors de sa connexion
    public function deHashPassword($password, $password_hash)
    {
        return ((password_verify($password, $password_hash))) ?? false;
    }
    // nous renseignons les informations avec les arguments demandé
    public function sentEmailTo(string $to, string $subject, string $body)
    {

        // si on se trouve sur un serveur local on utilise phpmailer
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            $phpmailer = new PHPMailer();

            // ici c'est la configuration su server de messagerie mailtrap
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = '1115a6ea5b4a74';
            $phpmailer->Password = '4fa1184cc03b34';
            // ici on immagine avoir une messagerie 
            $phpmailer->setFrom('forum@gmail.com', 'Services Forum');
            // on indique a PHPMailer l'adresse de destination 
            $phpmailer->addAddress($to, 'Recipient Name');
            // Sujet de l'e-mail
            $phpmailer->Subject = $subject;
            // Contenu de l'e-mail 
            $phpmailer->isHTML(true);
            // Indique que le contenu est au format HTML
            $phpmailer->CharSet = 'UTF-8';
            // Définit l'encodage des caractères
            $phpmailer->Body = $body;
            // on envois le mail et retournons la reponse de l'envois (true or false)
            return $phpmailer->send();
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
