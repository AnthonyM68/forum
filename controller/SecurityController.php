<?php

namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Session;

use Model\Managers\UserManager;
use Model\Managers\SecurityManager;
use DateTime;

class SecurityController extends AbstractController
{
    // contiendra les méthodes liées à l'authentification : register, login et logout
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = filter_input(
                INPUT_POST,
                'password',
                FILTER_VALIDATE_REGEXP,
                array(
                    "options" => array(
                        // 12 et 25 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial :
                        // `/(?=.[A-Z])(?=.[a-z])(?=.\d)(?=.[!@#$%^&*()_+}{:;'?/>,-])(?!.*\s).{12,25}/`
                        "regexp" => "/.{6,25}/"
                    )
                )
            );
            $repeat_password = filter_input(
                INPUT_POST,
                'repeat_password',
                FILTER_VALIDATE_REGEXP,
                array(
                    "options" => array(
                        // `/(?=.[A-Z])(?=.[a-z])(?=.\d)(?=.[!@#$%^&*()_+}{:;'?/>,-])(?!.*\s).{12,25}/`
                        "regexp" => "/.{6,25}/"
                    )
                )
            );
            $username = filter_input(
                INPUT_POST,
                'username',
                FILTER_VALIDATE_REGEXP,
                array(
                    // "/^[a-zA-Z0-9_]{6,25}$/"
                    "options" => array("regexp" => "/^[a-zA-Z0-9_]{6,25}$/")
                )
            );
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            // si toutes les entrées sont vérifiées
            if ($username && $email && $password && $repeat_password && $_POST['rgpd']) {
                $userManager = new UserManager();
                // si l'email existe
                // XSS
                $emailExist = $userManager->searchIfEmailExist($email);
                if ($emailExist) {
                    // l'email existe donc l'user est déjà inscrit
                    Session::addFlash("success", "Vous êtes déjà inscrit");
                    $this->redirectTo("home", "index");
                }
                // si les password ne respectent pas les critères de soumission
                if (!$password || !$repeat_password) {
                    // on affiche une alert 
                    Session::addFlash("warning", "Les mots de passe ne respectent pas les critères de soumission");
                    $this->redirectTo("home", "index");
                }
                // si les password ne sont pas égaux
                if ($password !== $repeat_password) {
                    // on affiche une alert 
                    Session::addFlash("error", "Les mots de passe ne sont pas identique");
                    $this->redirectTo("home", "index");
                }
                // générer un token unique de vérification 
                // CSRF
                $token = $this->generateTokenUnique();

                // on recherche si l'username existe
                $usernameExist = $userManager->searchIfUsernamelExist($username);

                if (!$usernameExist) {
                    $date = new DateTime("now");
                    $userManager->add([
                        "username" => $username,
                        "password" => password_hash($repeat_password, PASSWORD_DEFAULT),
                        "email" => $email,
                        "token" => $token,
                        "dateRegister" => $date->format('Y-m-d H:i:s'),
                        "token_validity" => null,
                        "role" => json_encode([
                            "ROLE_SUBSCRIBER" // l'insciption n'étant pas fini il reste visiteur
                        ]),
                    ]);
                    // EMAIL 
                    // sujet
                    $subject = "Merci pour votre inscription au Forum@";
                    // contenu
                    $content = "<p>Veuillez confirmer votre inscription en cliquant sur le lien suivant:</p>
                <a href='http://localhost/forum/index.php?ctrl=security&action=login&token=" . $token . "'>Cliquez ici</a>";
                    // Envoyer EMAIL
                    $result = $this->sentEmailTo($email, $subject, $content);

                    $result ? Session::addFlash("success", "Votre inscription est terminée, veuillez vérifier vos email")
                        : Session::addFlash("error", "Une erreur est survenue lors de l'envois de la confirmation par Email");;
                } else Session::addFlash("error", "Nom d'utilisateur déjà utilisé");
                // on redirige sur le home
                $this->redirectTo("home", "index");
            }
            var_dump("test");
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
        // on filtre le token bin2hex {32} caractères
        $token = filter_input(INPUT_GET, 'token', FILTER_VALIDATE_REGEXP, array(
            "options" => array("regexp" => "/^[a-f0-9]{64}$/")
        ));
        // on filtre toutes les entrées
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/.{6,25}/")));

        // si la requête provient d'une inscription et validation par email
        if (isset($_GET['token'])) {
            // si le token est vérifié
            if ($token) {
                $userManager = new UserManager();
                // si l'on trouve le token en bdd on le vide 
                $userInfos = $userManager->searchIfTokenlExist($token);
                // s'il n'existe pas
                if (!$userInfos) {
                    Session::addFlash("warning", "L'inscription à déjà été confirmer");
                    $this->redirectTo("home", "index");
                }
                // s'il existe un token en bdd pour cet user
                if (!empty($userInfos->getToken())) {
                    // on nettoye le token devenu inutile
                    $resetToken = $userManager->resetToken($userInfos->getToken());
                    /**
                     *   ROLE UTILISATEUR
                     * */
                    $role = json_encode(["ROLE_USER"]);
                    if ($resetToken) {
                        // on mets à jour le rôle de l'utilisateur, à ce stade utilisateur inscrits et confirmé
                        $update = $userManager->updateRoleUser($role, $userInfos->getId());
                        if ($update) {
                            // on prépare l'email de confirmation
                            $subject = "Inscription confirmée";
                            $content = "<p>Votre inscription est confirmée</p>";
                            // on envoie l'email
                            $result = $this->sentEmailTo($userInfos->getEmail(), $subject, $content);
                            Session::addFlash("success", "Votre inscription a bien été validée");
                            // on redirige
                            $this->redirectTo("home", "index");
                        }
                        Session::addFlash("warning", "La mise à jour de l'utilisateur a échouée");
                    }
                }
            }
            Session::addFlash("warning", "La vérification a échouée, veuillez recommencer");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // si l'utilisateur se connecte
            if ($email && $password) {
                // on instancie UserManager
                $userManager = new UserManager();

                if (!$password) {
                    // on affiche une alert a l'utilisateur
                    Session::addFlash("error", "Le mot de passe ne respecte pas les critères de soumission");
                    $this->redirectTo("home", "index");
                }
                // on vérifie si l'email existe dans la base de données table user
                $userInfos = $userManager->searchPasswordByEmail($email);

                if (!$userInfos) {
                    // on affiche une alert a l'utilisateur
                    Session::addFlash("error", "Vous n'êtes pas inscrit sur le Forum");
                    $this->redirectTo("home", "index");
                }
                // on vérifie que le hash en bdd correspond au hash du password
                $checkPassword = password_verify($password, $userInfos->getPassword());
                // si c'est le cas on met les infos de l'utilisateur en $_session
                if ($checkPassword) {
                    $infosSession = $userManager->infosUserConnectSession($email);
                    Session::addFlash("success", "Bienvenue " . $infosSession->getUsername());
                    Session::setUser($infosSession);
                }
                $this->redirectTo("home", "index");
            }
        }
        return [
            "view" => VIEW_DIR . "security/loginSignin.php",
            "meta_description" => "Connectez-vous pour participer au Forum",
            "data" => []
        ];
    }
    public function logout()
    {
        if ($_SESSION['user']) {
            unset($_SESSION['user']);
            Session::addFlash("success", "Au revoir " . $_SESSION['username']);
        }

        $this->redirectTo("home", "index");
    }
    public function profile()
    {
        $userManager = new UserManager();
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "profile",
            "meta_description" => "Profil utilisateur",
            "data" => [
                // on recherche les infos utilisateur hormis le password
                "user" => $userManager->infoWithoutPassword($_SESSION['user']->getId())
            ]
        ];
    }

    public function allUsers()
    {
        $manager = new UserManager();
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "usersList",
            "meta_description" => "Liste des utilisateurs du forum",
            "data" => [
                "users" => $manager->findAll(['dateRegister', 'DESC'])
            ]
        ];
    }
    public function deleteAccount()
    {
        $userManager = new UserManager();
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $userInfos = $userManager->searchIfTokenlExist($token);
            var_dump($userInfos);
            // si l'utilisateur est trouvé tout a été vérifier
            // on procéde a l'anonymisation
            if ($userInfos) {

                /******** PSEUDO ANONYMISATION ***************/
                // on serialize les données de l'utilisateur
                $userData = json_encode($userInfos);
               
                $securityManager = new SecurityManager();
                // on chiifre les données
                /*$encryptedUser = AbstractController::encryptData($userData);
                // on génére un token unique un cas de renoncement de l'utilisateur après suppression
                $token = $this->generateTokenUnique();
                
                // on ajoute les données dans la table de chiffrement identifiable par token unique remis a l'utilisateur
                $result = $securityManager->addDataEncrypt($encryptedUser['encrypted_data'], $encryptedUser['iv'], $userInfos->getId(), $token);
                // si tout se passe bien on envoie un mail a l'utilisateur
                if ($result) {
                    $subject = "Compte supprimé";
                    // il disposera de 30 jours avant suppression définitive
                    $content = "<p>Votre compte a bien été supprimé.Cependant vous disposez d'un délai de 30 jours si toutefois vous changez d'avis. 
                    Pour retrouver votre compte et toutes ces fonctionnalités vous pouvez le réactiver en cliquant sur le lien suivant.
                    Au delà de ce délai, vos données seront de manière irréverssible détruites. </p>
                 <a href='http://localhost/forum/index.php?ctrl=security&action=restorAccount&token=" . $token . "'>Cliquez ici</a>";
                    // on envois l'email
                    $result = $this->sentEmailTo($userInfos->getEmail(), $subject, $content);
                    Session::addFlash("success", $userInfos->getUsername() . "Compte désactiver et anonymiser pendant 30 jours avant anonymisation, veuillez vérifier vos Email");
                   
                } else {
                    Session::addFlash("error", "La Pseudo Anonymisation a échoué veuillez soumettre a nouveau votre demande");
                    $this->redirectTo("home", "index");
                }*/
                /******** ANONYMISATION ***************/

                $hashedData = $this->hashDataUser($userInfos);
                var_dump($hashedData);
                $result = $userManager->updateDataHashed($hashedData);
                var_dump($result);
     




            } else {
                Session::addFlash("warning", "La vérification a échouée");
            }
            //$this->redirectTo("home", "index");
        }
        die;

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $password = filter_input(
            INPUT_POST,
            'password',
            FILTER_VALIDATE_REGEXP,
            array(
                "options" => array(
                    // 12 et 25 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial :
                    // `/(?=.[A-Z])(?=.[a-z])(?=.\d)(?=.[!@#$%^&*()_+}{:;'?/>,-])(?!.*\s).{12,25}/`
                    "regexp" => "/.{6,25}/"
                )
            )
        );
        $repeat_password = filter_input(
            INPUT_POST,
            'repeat_password',
            FILTER_VALIDATE_REGEXP,
            array(
                "options" => array(
                    // `/(?=.[A-Z])(?=.[a-z])(?=.\d)(?=.[!@#$%^&*()_+}{:;'?/>,-])(?!.*\s).{12,25}/`
                    "regexp" => "/.{6,25}/"
                )
            )
        );
        if ($id && $password && $repeat_password) {
            // si les password ne respectent pas les critères de soumission
            if (!$password || !$repeat_password) {
                // on affiche une alert 
                Session::addFlash("warning", "Les mots de passe ne respectent pas les critères de soumission");
                $this->redirectTo("home", "index");
            }
            // si les password ne sont pas égaux
            if ($password !== $repeat_password) {
                // on affiche une alert 
                Session::addFlash("error", "Les mots de passe ne sont pas identique");
                $this->redirectTo("home", "index");
            }
            // on recherche les infos utilisateur par son id
            $userInfos = $userManager->searchForDeleteAccount($id);
            // on vérifie que le hash en bdd correspond au hash du password
            $checkPassword = password_verify($password, $userInfos->getPassword());
            // si c'est le cas 
            if ($checkPassword) {
                // on génére un token unique
                $token = $this->generateTokenUnique();
                // on insert le token de l'user dans la bdd
                $updateToken = $userManager->updateToken($token, $userInfos->getId());
                // on prépare l'email de confirmation
                $subject = "Confirmation de suppression de compte";
                $content = "<p>Veuillez cliquer sur le lien suivant afin de confirmé la suppression de votre compte</p>
                 <a href='http://localhost/forum/index.php?ctrl=security&action=deleteAccount&token=" . $token . "'>Cliquez ici</a>";
                // on envois l'email
                $result = $this->sentEmailTo($userInfos->getEmail(), $subject, $content);
                Session::addFlash("success", $userInfos->getUsername() . "Votre demande est bien prise en compte, veuillez vérifier vos Email");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("warning", $_SESSION['user']->getUsername() . " Pour supprimer votre compte vous devez indiqué et confirmé votre mot de passe.");
        }
        $userInfos = $userManager->infoWithoutPassword($id);
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "delete-account",
            "meta_description" => "Profil utilisateur",
            "data" => [
                "user" => $userInfos
            ]
        ];
    }
    public function modifyAccount()
    {
        Session::addFlash("warning", $_SESSION['user']->getUsername() . " cette section reste a terminée");
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "profile",
            "meta_description" => "Profil utilisateur",
            "data" => [
                "user" => $_SESSION['user']
            ]
        ];
    }
}
