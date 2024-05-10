<?php

/**
 * FORUM CONTROLLER
 */

namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;
use Model\Managers\UserManager;
//use Model\Managers\EmailManager;

use DateTime;
// on indique a l'application ou trouver le générateur de données fictifs
use Faker\Factory;

class ForumController extends AbstractController implements ControllerInterface
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
    }

    public function listCategories()
    {
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode 
        // findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "ASC"]);
        // le controller communique avec la vue "listCategories" (view) pour lui envoyer la liste des catégories (data)
        return [
            "view" => VIEW_DIR . "forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }
    /**
     * On recherche toutes les catégories pour la soumission d'un post
     */
    public function findAllCategories()
    {
        $categoryManager = new CategoryManager();
        return $categoryManager->findAll();
    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function findLast5PostsByTopic($id)
    {
        $postManager = new PostManager();
        return $postManager->findLast5PostsByTopic($id);
    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function listTopicsByCategory($id): array
    {
        $categoryManager = new CategoryManager();
        $topicManager = new TopicManager();
        // changer de methode plutot que findOneById
        // besoin de convertir la date lors de la sortie sql
        $category = $categoryManager->findOneById($id);
        $topics = $topicManager->findTopicsByCategory($id);
        return [
            "view" => VIEW_DIR . "forum/listTopicsByCategory.php",
            "meta_description" => "Liste des topics par catégorie : " . $category,
            "data" => [
                "category" => $category,
                "topics" => $topics
            ]
        ];
    }
    /**
     * Regroupe tous les posts d'un topic
     *
     * @param [type] $id
     * @return void
     */
    public function listPostByIdTopic($id): array
    {
        $postManager = new PostManager();
        $posts = $postManager->findAllByIdTopic($id);
        foreach ($posts as $post) {
            var_dump($post);
        }
        return [
            "view" => VIEW_DIR . "forum/topic.php",
            "section" => "topic",
            "meta_description" => "Topic: ",
            "data" => [
                "posts" => $posts
            ]
        ];
    }
    /**
     * Fournit les 5 derniers Topics pour la section news
     *
     * @return void
     */
    public function listLast5Topics(): array
    {
        $topicManager = new TopicManager();
        $topics = $topicManager->findLast5Topics();
        return [
            "view" => VIEW_DIR . "forum/home.php",
            "meta_description" => "Liste des topics : ",
            "data" => [
                "topics" => $topics
            ]
        ];
    }
    /**
     * Fournit les 5 derniers posts pour la section news
     *
     * @return void
     */
    public function listLast5Posts(): array
    {
        $topicManager = new PostManager();
        $posts = $topicManager->findLast5Posts();
        return [
            "view" => VIEW_DIR . "forum/home.php",
            "meta_description" => "Liste des articles : ",
            "data" => [
                "posts" => $posts
            ]
        ];
    }
    /**
     * recherche toutes les infos d'un topic par son id
     *
     * @return le topic et tous ces posts
     */
    public function showFullTopic()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $topicManager = new TopicManager();
            $topic = $topicManager->findOneByIdTopic($id);

            $postManager = new PostManager();
            $posts = $postManager->findAllByIdTopic($id);
        }
        return [
            "view" => VIEW_DIR . "forum/topic.php",
            "section" => "edit-topic",
            "meta_description" => "Ajouter un Article : ",
            "data" => [
                "topic" => $topic,
                "posts" => $posts
            ]
        ];
    }

    /**
     * Ajouter une catégorie 
     *
     * @return void
     */
    public function addCategory(): array
    {
        $this->restrictTo("ROLE_USER");

        if (isset($_POST['name']) && !empty($_POST['name'])) {
            // CSRF
            if (isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {

                $nameCategory = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
                $categoryManager = new CategoryManager();
                $result = $categoryManager->add(["name" => $nameCategory]);
                if ($result) {
                    Session::addFlash("success", "Catégorie ajouté avec succès");
                } else {
                    Session::addFlash("error", "Erreur lors de la soumission de la catégorie");
                }
                $this->redirectTo("forum", "index");
            }
        }
        return [
            "view" => VIEW_DIR . "forum/published.php",
            "section" => "category",
            "meta_description" => "Ajouter une catégorie : ",
            "data" => []
        ];
    }
    /**
     * Ajouter un Topic
     *
     * @return void
     */
    public function addTopic(): array
    {
        $this->restrictTo("ROLE_USER");
        // on filtre les entrées
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        $category_id = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
        // si elles sont toutes vérifiées
        if ($title && $content && $category_id) {
            // CSRF
            if (isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {
                $topicManager = new TopicManager();
                $date = new DateTime();
                $id_topic = $topicManager->add([
                    "title" => $title,
                    "dateCreation" => $date->format('Y-m-d H:i:s'),
                    "category_id" => $category_id,
                    "user_id" => 1
                ]);
                $postManager = new PostManager();
                $result = $postManager->add([
                    "content" => $content,
                    "dateCreation" => $date->format('Y-m-d H:i:s'),
                    "topic_id" => $id_topic
                ]);

                if ($result) {
                    Session::addFlash("success", "Votre Topic a bien été sauvegarder");
                    $this->redirectTo("home", "index");
                } else {
                    Session::addFlash("error", "Une erreur est survenue veuillez recommencer");
                }
            }
        }
        return [
            "view" => VIEW_DIR . "forum/published.php",
            "section" => "topic",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
        ];
    }

    /**
     * Ajouter un Post
     *
     * @return void
     */
    public function addPost()
    {
        // on filtre les entrées
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        $topic_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $topicManager = new TopicManager();
        $postManager = new PostManager();
        //if (isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {
        // si elles sont toutes vérifiées
        if ($content && $topic_id) {

            $date = new DateTime();
            $result = $postManager->add([
                "content" => htmlspecialchars($content),
                "dateCreation" => $date->format('Y-m-d H:i:s'),
                "topic_id" => $topic_id,
                "user_id" => Session::getUser()->getId()
            ]);
            if ($result) {
                Session::addFlash("success", "Votre Article a bien été sauvegarder");
            } else {
                Session::addFlash("error", "Une erreur est survenue veuillez recommencer");
            }
        }
        return [
            "view" => VIEW_DIR . "forum/topic.php",
            "section" => "edit-topic",
            "meta_description" => "Ajouter un Article : ",
            "data" => [
                "topic" => $topicManager->findOneByIdTopic($topic_id),
                "posts" => $postManager->findAllByIdTopic($topic_id)
            ]
        ];
    }
}
