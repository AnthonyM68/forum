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

        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode 
        // findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "ASC"]);
        Session::addFlash("success", "Catégorie ajouté avec succès");

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
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
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
    /**
     * Undocumented function
     *
     * @return void
     */
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

                "topics" => $topics
            ]
        ];
    }
    /**
     * Undocumented function
     *
     * @return void
     */
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
                "posts" => $posts
            ]
        ];
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function addCategory()
    {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $nameCategory = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            $categoryManager = new CategoryManager();
            $result = $categoryManager->add(["name" => $nameCategory]);
            if ($result) {
                $_SESSION['success'] = "Catégorie ajouté avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la soumission de la catégorie";
            }
            $this->redirectTo("forum", "index");
        }
        return [
            "view" => VIEW_DIR . "forum/published.php",
            "section" => "category",
            "meta_description" => "Ajouter une catégorie : ",
            "data" => []
        ];
    }
    /**
     * Undocumented function
     *
     * @return void
     */
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
                "user_id" => 1
            ]);


            $postManager = new PostManager();
            $result = $postManager->add([
                "content" => $content,
                "dateCreation" => $date->format('Y-m-d H:i:s'),
                "topic_id" => $id_topic
            ]);

            if ($result) {
                $_SESSION["success"] = "Votre Topic a bien été sauvegarder";
            } else {
                $_SESSION["error"] = "Une erreur est survenue veuillez recommencer";
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
     * Undocumented function
     *
     * @return void
     */
    public function addPost()
    {
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

            if ($result) {
                $_SESSION["success"] = "Votre Article a bien été sauvegarder";
            } else {
                $_SESSION["error"] = "Une erreur est survenue veuillez recommencer";
            }
        } else if (isset($_GET['id']) && !empty($_GET['id'])) {
            return [
                "view" => VIEW_DIR . "forum/addPost.php",
                "section" => "post",
                "meta_description" => "Ajouter un Article : ",
                "id" => [
                    "id_topic" => $_GET['id']
                ]
            ];
        }
        return [
            "view" => VIEW_DIR . "forum/published.php",
            "section" => "post",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
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
    public function findAllPostByIdTopicLIMIT($id)
    {
        $postManager = new PostManager();
        return $postManager->findAllPostByIdTopicLIMIT($id);
    }
    /******************************************************** */
    /**
     * Ceci est une méthode de création de topic a la vollée
     * en utilisant la dépendance Faker, pour le dévellopement de cette
     * application et les suivantes ;)
     * 
     * ONLY FOR DEVELOPMENT
     * @return void
     */
    public function fakerTopicWithFirstPost()
    {
        echo "fakerTopicWithFirstPost";
        // on crée une région de format de données
        /* $fakerFr = Factory::create('fr_FR');
        $min = 1;
        $max = 10;

        $topicManager = new TopicManager();
        $postManager = new PostManager();
        $date = new DateTime();

        for ($i = 0; $i < 10; $i++) {

            $category_id = rand($min, $max);

            $id_topic = $topicManager->add([
                "title" => $fakerFr->sentence,
                "dateCreation" => $fakerFr->date('Y-m-d') . ' ' . $fakerFr->time('H:i:s'),
                "category_id" => $category_id,
                "user_id" => rand(1, 2) // prevoir rand() quand il y aura une liste utilisateurs
            ]);
            $id_topic = rand(1, $id_topic);
            $result = $postManager->add([
                "content" => $fakerFr->sentence,
                "dateCreation" => $fakerFr->date('Y-m-d') . ' ' . $fakerFr->time('H:i:s'),
                "topic_id" => $id_topic
            ]);
        }
        return [
            "view" => VIEW_DIR . "forum/addTopic.php",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
        ];*/
    }
    public function dropTable()
    {
        echo "dropTable";
        /*$topicManager = new TopicManager();
        $allIdFromTable = $topicManager->findAllId("id_topic");
        $postManager = new PostManager();
        if ($allIdFromTable) {
            foreach ($allIdFromTable as $topicId) {
                $postManager->deleteByTopicId($topicId->getId());
                $topicManager->delete($topicId->getId());
            }
        }
        return [
            "view" => VIEW_DIR . "forum/addPost.php",
            "meta_description" => "Ajouter un Article : ",
            "data" => []
        ];*/
    }
}
