<?php

namespace App;
// on indique le namespace de la dépendance pour que la class PHPMailer soit trouvée
use PHPMailer\PHPMailer\PHPMailer;
// manipulation des propriétées de l'objet 
use ReflectionObject;
use DateTime;
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
    // 32 bytes aes-256-cbc
    private const PRIVATE_KEY = 'HJIeGmF8zwE9Z4B9Eo7oC+IBDG9I0HXEyBWEyRuQqnHI2o8pIOQQ+T51ECOl8rD8';
    // 16 bytes init vector
    private static $ivVectorInit;

    public function redirectTo($ctrl = null, $action = null, $id = null)
    {
        $url = $ctrl ? "?ctrl=" . $ctrl : "";
        $url .= $action ? "&action=" . $action : "";
        $url .= $id ? "&id=" . $id : "";

        header("Location: $url");
        die();
    }

    public function restrictTo($role): void
    {
        // s'il n'y a pas de session de démarrer
        if (!Session::getUser() || !Session::getUser()->hasRole($role)) {
            $this->redirectTo("security", "login");
        }
        return;
    }
    public function generateTokenUnique(): string
    {
        $length = 32;
        // méthode pour générer un jeton unique
        return  bin2hex(random_bytes($length));
    }
    // méthode pour hasher un mot de passe
    public function generatePasswordHash($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    // méthode pour vérifer si un hash récupéré dans la base de données correspond
    // au mot de pass clair saisie par l'utilisateur lors de sa connexion
    public function deHashPassword($password, $password_hash): bool
    {
        return ((password_verify($password, $password_hash))) ?? false;
    }
    // hash d'un tableau de données utilisateur 
    public static function hashDataUser($user)
    {
        // on utiliser la réflexion d'objet pour obtenir les propriétés de l'objet User
        $reflection = new ReflectionObject($user);

        $properties = $reflection->getProperties();

        $hashedData = [];
        // Parcourir les propriétés de l'objet et hash des données
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($user);

            // Hash si valeur non null, non vide
            if ($propertyValue !== null && $propertyValue !== "" 
            && $propertyName !== "id" && $propertyName !== "role" 
            && $propertyName !== "dateRegister" 
            && $propertyName !== "tokenValidity") {
                $hashedValue = password_hash($propertyValue, PASSWORD_DEFAULT);
            }
            // on défini une date d'expiration à 30j
            if ($propertyName === "tokenValidity") {
                $date = new DateTime();
                // 30 jours de délai avant suppression définitive
                $date->modify('+30 days');
                $tokenValidity = $date->format('Y-m-d H:i:s');
                $hashedValue = $tokenValidity;
            }
            // on préserve l'id en clair
            if ($propertyName === "id") {
                $propertyName = "id_user";
                $hashedValue = $propertyValue;
            }
            // on préserve la date d'inscription en clair format =>> DATETIME
            if ($propertyName === "dateRegister") {
                // format DateTime
                $expectedFormatRegex = '/^\d{2}\-\d{2}\-\d{4} \d{2}:\d{2}:\d{2}$/';
                // si la date est au format d'affichage on la convertit au format DATETIME
                if (!preg_match($expectedFormatRegex, $propertyValue)) {
                    // on convertit la date d'inscription pour la garder en clair
                    $date = DateTime::createFromFormat('d/m/Y H:i:s', $propertyValue);
                    $hashedValue = $date->format('Y-m-d H:i:s');
                } else {
                    $hashedValue = $propertyValue;
                }
            }
            // on préserve les rôles au format =>> JSON
            if ($propertyName === "role") {
                $hashedValue = $propertyValue;
            }
            // on pousse dans le nouveau tableau sous la forme clé valeur
            $hashedData[$propertyName] = $hashedValue;
            $hashedValue = NULL;
        }
        return $hashedData;
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
    }
    public static function convertToString($roles): string
    {
        if (is_array($roles)) {
            // on retir le dernier élément du tableau
            $lastElement = array_pop($roles);
            // on initialise une string vide
            $formattedRoles = "";
            // s'il reste des rôles dans le tableau
            foreach ($roles as $userRoles) {
                // on annalyse le contenu des rôles
                $formattedRoles .= ($userRoles === "ROLE_USER" ? "Membre du Forum" : ($userRoles === "ROLE_ADMIN" ? "Administrateur" : ""));
                // on ajoute la virgule
                $formattedRoles .= ", ";
            }
            // si la chaine existe et non vide
            if ($formattedRoles !== "") {
                // on retir la dernière virgule
                $formattedRoles = rtrim($formattedRoles, ", ");
                // on la remplace par un "et"
                $formattedRoles .= " et";
            }
            // on ajoute un petit espace
            $formattedRoles .= " ";
            // on analyse le dernier élément du tableau de rôles initial
            $formattedRoles .= $lastElement === "ROLE_USER" ? "Membre du Forum" : ($lastElement === "ROLE_ADMIN" ? "Administrateur" : "");
        }
        return $formattedRoles;
    }
    public static function encryptData($data)
    {
        // Générer un IV aléatoire
        $iv = openssl_random_pseudo_bytes(16); // IV de 16 octets pour AES-256-CBC
        // Vérifier la longueur de l'IV
        $ivLength = strlen($iv);
        if ($ivLength !== 16) {
            // La longueur de l'IV est incorrecte
            throw new Exception("Erreur : la longueur de l'IV est incorrecte.");
        }
    
        // Chiffrer les données avec l'IV généré
        $encryptedData = openssl_encrypt(
            $data,
            "AES-256-CBC",
            self::PRIVATE_KEY,
            0,
            $iv
        );
    
        // Retourner les données chiffrées et l'IV
        return [
            "encryptedData" => $encryptedData,
            "iv" => $iv
        ];
    }
    
    public static function decryptData($encryptedData, $ivector)
    {
        return openssl_decrypt(
            $encryptedData, // l'utilisateur a unsérialiser et chiffre SECRET
            "AES-256-CBC", // type de chiffrement
            self::PRIVATE_KEY, // clés privée de chiffrement
            0, // option facultatif
            $ivector // vecteur d'initialisation PAS FORCEMENT SECRET
        );
    }
}
