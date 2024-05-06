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
        // générer un token unique de vérification 
        // CSRF
        if(isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {
                   
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

            if ($username && $email && $password && $repeat_password && isset($_POST['rgpd']) && $_POST['rgpd'] === "on") {

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


                // on recherche si l'username existe
                $usernameExist = $userManager->searchIfUsernamelExist($username);

                $date = new DateTime();
                $date->modify('+30 days');
                $tokenValidity = $date->format('Y-m-d H:i:s');

                if (!$usernameExist) {
                    $date = new DateTime("now");
                    $tokenValidity = $date->modify('+30 days');
                    $result = $userManager->add([
                        "username" => $username,
                        "password" => password_hash($repeat_password, PASSWORD_DEFAULT),
                        "email" => $email,
                        "token" => $_SESSION['token'],
                        "tokenValidity" => $tokenValidity->format('Y-m-d H:i:s'),
                        "dateRegister" => $date->format('Y-m-d H:i:s'),
                        "role" => json_encode([
                            "ROLE_SUBSCRIBER" // l'insciption n'étant pas fini il reste visiteur
                        ]),
                    ]);
                    if ($result) {
                        // EMAIL 
                        // sujet
                        $subject = "Merci pour votre inscription au Forum@";
                        // contenu
                        $content = "<p>Veuillez confirmer votre inscription en cliquant sur le lien suivant:</p>
                        <a href='http://localhost/forum/index.php?ctrl=security&action=login&token=" . $_SESSION['token'] . "'>Cliquez ici</a>";
                        // Envoyer EMAIL
                        $result = $this->sentEmailTo($email, $subject, $content);
                        $result ? Session::addFlash("success", "Votre inscription est terminée, veuillez vérifier vos email")
                            : Session::addFlash("error", "Une erreur est survenue lors de l'envois de la confirmation par Email");
                        // on redirige sur le home
                        $this->redirectTo("home", "index");
                    } else {
                        Session::addFlash("error", "L'enregistrement en base de données a échoué");
                    }
                } else
                    Session::addFlash("error", "Nom d'utilisateur déjà utilisé");
            } else {
                Session::addFlash("error", "Vérifiez votre saisie quelque chose ne va pas");
            }
        }
        
        return [
            "view" => VIEW_DIR . "security/loginSignin.php",
            "section" => "register",
            "meta_description" => "Créer un compte pour participer au Forum",
            "data" => []
        ];
    }

    public function login()
    {
        // on filtre le token bin2hex {32} caractères
        $token = filter_input(INPUT_GET, 'token', FILTER_VALIDATE_REGEXP, array(
            "options" => array("regexp" => "/^[a-f0-9]{64}$/")
        )
        );
        // on filtre toutes les entrées
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/.{6,25}/")));

        // si la requête provient d'une inscription et validation par email
        if (isset($_GET['token'])) {

            die;
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
        /*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        }*/
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
            "restor" => false,
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
        // suppression des données utilisateurs par hashage
        // conservation des données chiffrés 
        // si le token est présent dans l'url
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            // on recherche les infos que l'on veux pseudo anonymiser
            $userInfos = $userManager->dataUserPseudoAnonymsize($token);
            // si l'utilisateur est trouvé tout a été vérifier
            // on procéde a l'anonymisation
            if ($userInfos) {
                /******** PSEUDO ANONYMISATION TABLE DATAS_ENCRYPTED***************/
                // on serialize les données de l'utilisateur
                $userData = serialize($userInfos);
                $securityManager = new SecurityManager();
                // on chiifre les données
                $encryptedUser = AbstractController::encryptData($userData);
                // 30 jours de délai avant suppression définitive
                $date = new DateTime();
                $date->modify('+30 days');
                // on génére un token unique un cas de renoncement de l'utilisateur après suppression
                $token = $this->generateTokenUnique();
                $tokenValidity = $date->format('Y-m-d H:i:s');
                // on ajoute les données à la table de chiffrement identifiable par token unique remis a l'utilisateur
                $result = $securityManager->addDataEncrypted($encryptedUser['encryptedData'], $encryptedUser['iv'], $userInfos->getId(), $token, $tokenValidity);
                if ($result) {
                    // on hash les données de l'utilisateur dans la table user
                    $hashedData = $this->hashDataUser($userInfos);
                    if ($hashedData) {
                        /******** ANONYMISATION TABLE USER ***************/
                        $result = $userManager->updateDataHashed($hashedData);
                        if ($result) {
                            $subject = "Compte supprimé";
                            // il disposera de 30 jours avant suppression définitive
                            $content = "<p>Votre compte a bien été supprimé.Cependant vous disposez d'un délai de 30 jours si toutefois vous changez d'avis. 
                            Pour retrouver votre compte et toutes ces fonctionnalités vous pouvez le réactiver en cliquant sur le lien suivant.
                            Au delà de ce délai, vos données seront de manière irréverssible détruites. </p>
                         <a href='http://localhost/forum/index.php?ctrl=security&action=restorAccount&token=" . $token . "'>Cliquez ici</a>";
                            // on envois l'email
                            $result = $this->sentEmailTo($userInfos->getEmail(), $subject, $content);
                            $this->logout();
                            Session::addFlash("success", $userInfos->getUsername() . "Compte désactiver et anonymiser pendant 30 jours avant anonymisation, veuillez vérifier vos Email");
                        } else {
                            Session::addFlash("error", "Erreur lors de l'insertion des données hashées");
                        }
                    } else {
                        Session::addFlash("error", "La Pseudo Anonymisation a échoué veuillez soumettre a nouveau votre demande");
                    }
                } else {
                    Session::addFlash("error", "Erreur lors de l'insertion des données encryptées");
                }
            } else {
                Session::addFlash("warning", "La vérification a échouée où l'anonymisation a déjà eu lieu");
            }
            Session::addFlash("warning", "La vérification a échouée où l'anonymisation a déjà eu lieu");
            die;
            $this->redirectTo("home", "index");
        }
        /**
         * si la confirmation par mot de passe est soumise
         */
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
        // Si l'utilisateur a confirmé la suppression par mot de passe
        // on lui renvoie son id pour l'insérer dans le form de confirmation par mot de passe
        // que JS va afficher pour confirmer la demande de suppression
        if (!$password && !$repeat_password && !isset($_GET['token'])) {
            echo json_encode([
                "id" => Session::getUser()->getId()
            ]);
            die;
        }
        // Si les informations sont bien vérifiés
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
            $this->redirectTo("home", "index");
        }
    }
    public function modifyAccount()
    {
        Session::addFlash("warning", $_SESSION['user']->getUsername() . " Token:" . $_GET['token'] . "");
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "profile",
            "meta_description" => "Profil utilisateur",
            "data" => [
                "user" => $_SESSION['user']
            ]
        ];
    }
    public function restorAccount()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $securityManager = new SecurityManager();
            $encryptedUser = $securityManager->searchIfTokenlExist($token);
            var_dump($encryptedUser);

            $decryptedUser = AbstractController::decryptData($encryptedUser->getEncryptedData(), $encryptedUser->getIv());
            $decryptedUser = unserialize($decryptedUser);


            if (isset($_GET['id'])) {
                $id = $_GET['id'];
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
                if ($password && $repeat_password) {
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

                    $userManager = new UserManager();
                    $result = $userManager->updateAfterRestaur($decryptedUser->getUsername(), $decryptedUser->getPassword(), $decryptedUser->getEmail(), $id);
                    if ($result) {
                        $result = $securityManager->deleteFromTableEncrypted($encryptedUser->getId());
                        if ($result) {
                            $subject = "Bon retour sur le Forum";
                            $content = "<p>Vous retrouvez toues les fonctionnalités du Forum</p>";
                            // on envois l'email
                            $result = $this->sentEmailTo($decryptedUser->getEmail(), $subject, $content);
                            Session::addFlash("success", "Bon retour parmis nous vous êtes désormais à nouveau identifiable.");
                            $this->redirectTo("home", "index");
                        }
                    } else {
                        Session::addFlash("warning", "Une erreur est survenue vérifiez vos données");
                    }
                } else {
                    Session::addFlash("warning", "Vous devez remplacez votre mot de passe");
                }
            }
        }
        return [
            "view" => VIEW_DIR . "security/users.php",
            "section" => "profile",
            "restor" => true,
            "token" => $token,
            "meta_description" => "Profil utilisateur",
            "data" => [
                "user" => $decryptedUser
            ]
        ];
    }
}
