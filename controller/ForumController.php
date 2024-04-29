<?php

namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;
use Model\Managers\EmailManager;
use DateTime;
class ForumController extends AbstractController implements ControllerInterface
{

    public function index()
    {

        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode 
        // findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "DESC"]);

        // le controller communique avec la vue "listCategories" (view) pour lui envoyer la liste des catégories (data)
        return [
            "view" => VIEW_DIR . "forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }

    public function listTopicsByCategory($id)
    {

        $topicManager = new TopicManager();
        $categoryManager = new CategoryManager();
        $category = $categoryManager->findOneById($id);
        $topics = $topicManager->findTopicsByCategory($id);

        return [
            "view" => VIEW_DIR . "forum/listTopics.php",
            "meta_description" => "Liste des topics par catégorie : " . $category,
            "data" => [
                "category" => $category,
                "topics" => $topics
            ]
        ];
    }
    public function listLast5Topics()
    {

        $topicManager = new TopicManager();
        //$categoryManager = new CategoryManager();
        //$category = $categoryManager->findOneById($id);
        $topics = $topicManager->findLast5Topics();
        return [
            "view" => VIEW_DIR . "forum/home.php",
            "meta_description" => "Liste des topics : ",
            "data" => [
                //"category" => $category,
                "topics" => $topics
            ]
        ];
    }
    public function listLast5Posts()
    {

        $topicManager = new PostManager();
        //$categoryManager = new CategoryManager();
        //$category = $categoryManager->findOneById($id);
        $posts = $topicManager->findLast5Posts();
        return [
            "view" => VIEW_DIR . "forum/home.php",
            "meta_description" => "Liste des articles : ",
            "data" => [
                //"category" => $category,
                "posts" => $posts
            ]
        ];
    }
    public function addCategory()
    {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $nameCategory = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            $categoryManager = new CategoryManager();
            $result = $categoryManager->add(["name" => $nameCategory]);

        }
        return [
            "view" => VIEW_DIR . "forum/addCategory.php",
            "meta_description" => "Ajouter une catégorie : ",
            "data" => []
        ];
    }
    public function addTopic()
    {
        if (isset($_POST['title']) && !empty($_POST['title']
        && isset($_POST['content']) && !empty($_POST['content'])
        && isset($_POST['category']) && !empty($_POST['category']))) {

            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
            $category_id = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);

            $topicManager = new TopicManager();
            $date = new DateTime();

            $id_topic = $topicManager->add([
                "title" => $title, 
                "dateCreation" => $date->format('Y-m-d H:i:s'),
                "category_id" => $category_id,
                "user_id" => Session::getUser()->getId()
            ]);
            
            $postManager = new PostManager();
            $result = $postManager->add([
                "content" => $content, 
                "dateCreation" => $date->format('Y-m-d H:i:s'),
                "topic_id" => $id_topic
            ]);

            if($result) {
                $_SESSION["success"] = "Votre Topic a bien été sauvegarder";
            } else {
                $_SESSION["error"] = "Une erreur est survenue veuillez recommencer";
            }
        }
        return [
            "view" => VIEW_DIR . "forum/addTopic.php",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
        ];
    }

    public function addPost()
    {
        var_dump($_POST);
        if (isset($_POST['content']) && !empty($_POST['content'])) {
            $contentPost = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
            $topic_id = filter_input(INPUT_POST, 'topic_id', FILTER_VALIDATE_INT);


            $categoryManager = new CategoryManager();
            $date = new DateTime();
            $result = $categoryManager->add([
                "content" => $contentPost, 
                "dateCreation" => $date->format('Y-m-d H:i:s'),
                "topic_id" => $topic_id
            ]);
            
            if($result) {
                $_SESSION["success"] = "Votre Article a bien été sauvegarder";
            } else {
                $_SESSION["error"] = "Une erreur est survenue veuillez recommencer";
            }
        } else if (isset($_GET['id']) && !empty($_GET['id'])) {
            return [
                "view" => VIEW_DIR . "forum/addPost.php",
                "meta_description" => "Ajouter un Article : ",
                "id" => [
                    "id_topic" => $_GET['id']]
            ];

        }
        return [
            "view" => VIEW_DIR . "forum/addPost.php",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
        ];
    }
    /**
     * On recherche toutes les catégories pour la soumission d'un post
     */
    public function findAllCategories()
    {
        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        return $categoryManager->findAll();
    }
}
