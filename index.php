<?php

namespace App;
// Inclure l'autoloader de Faker pour générer des données 
require_once 'vendor/autoload.php';

// on iclude la dépendance qui nous permet l'envois d'email d en local
// si le site est réellement héberger cette méthode sera ignoré et la fonction
// native mail() de php sera utiliser
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';

define('DS', DIRECTORY_SEPARATOR); // le caractère séparateur de dossier (/ ou \)
// meilleure portabilité sur les différents systêmes.
define('BASE_DIR', dirname(__FILE__) . DS); // pour se simplifier la vie
define('VIEW_DIR', BASE_DIR . "view/");   //le chemin où se trouvent les vues
define('PUBLIC_DIR', "public/");     //le chemin où se trouvent les fichiers publics (CSS, JS, IMG)

define('DEFAULT_CTRL', 'Home'); //nom du contrôleur par défaut
define('ADMIN_MAIL', "admin@gmail.com"); //mail de l'administrateur

require("app/Autoloader.php");

Autoloader::register();

//démarre une session ou récupère la session actuelle
session_start();
//et on intègre la classe Session qui prend la main sur les messages en session
use App\Session as Session;

//---------REQUETE HTTP INTERCEPTEE-----------
$ctrlname = DEFAULT_CTRL; //on prend le controller par défaut
//ex : index.php?ctrl=home
if (isset($_GET['ctrl'])) {
    $ctrlname = $_GET['ctrl'];
}
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
//on vérifie que le namespace pointe vers une classe qui existe
if (!class_exists($ctrlNS)) {
    //si c'est pas le cas, on choisit le namespace du controller par défaut
    $ctrlNS = "controller\\" . DEFAULT_CTRL . "Controller";
}
$ctrl = new $ctrlNS();

$action = "index"; //action par défaut de n'importe quel contrôleur
//si l'action est présente dans l'url ET que la méthode correspondante existe dans le ctrl
if (isset($_GET['action']) && method_exists($ctrl, $_GET['action'])) {
    //la méthode à appeller sera celle de l'url
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
} 
else $id = null;

if (isset($_GET['anchor'])) {
    $anchor = filter_input(INPUT_GET, 'anchor', FILTER_SANITIZE_SPECIAL_CHARS);
    $anchor = $anchor ? '#' . $anchor : ''; 
} 
else $anchor = null;
//ex : HomeController->users(null)
$result = $ctrl->$action($id);
// Générate token unique pour la session
Session::generateTokenUnique();

/*--------CHARGEMENT PAGE--------*/
if ($action == "ajax") { //si l'action était ajax
    //on affiche directement le return du contrôleur (càd la réponse HTTP sera uniquement celle-ci)
    echo $result;
} else {

    ob_start(); //démarre un buffer (tampon de sortie)
    include VIEW_DIR . "forum/sections/header/header.php";
    $header = ob_get_contents();
    ob_clean(); // Nettoie le tampon de sortie
    include VIEW_DIR . "forum/sections/news/news.php";
    $news = ob_get_contents();
    ob_clean(); // Nettoie le tampon de sortie
    // Inclusion du deuxième fichier et récupération du contenu
    include VIEW_DIR . "forum/sections/footer/footer.php";
    $footer = ob_get_contents();
    ob_clean(); // Nettoie le tampon de sortie
    $meta_description = $result['meta_description'];
    /* la vue s'insère dans le buffer qui devra être vidé au milieu du layout */
    include($result['view']);
    /* je place cet affichage dans une variable */
    $page = ob_get_contents();
    /* j'efface le tampon */
    ob_end_clean();
    /* j'affiche le template principal (layout) */
    include VIEW_DIR . "layout.php";
}
