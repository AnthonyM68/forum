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
            if (isset($_POST['token-form-link']) && $_POST['token-form-link'] === $_SESSION['token']) {
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

    /*************************TOPIC POSTS***************************** */
    public function deleteTopicAndPosts()
    {
        if (isset($_POST['token-form-link']) && $_POST['token-form-link'] === $_SESSION['token']) {
            if (isset($_GET['id'])) {
                $postManager = new PostManager();
                $topicManager = new TopicManager();

                $allPosts = $postManager->findAllIdPostByIdTopic($_GET['id']);

                if ($allPosts) {
                    foreach ($allPosts as $post) {
                        $result = $postManager->delete($post->getId());
                    }
                    if ($result) {
                        $result = $topicManager->delete($_GET['id']);
                    }
                }
                if ($result) {
                    Session::addFlash("success", "Topic et Posts supprimés");
                    $this->redirectTo("home", "index");
                } else {
                    Session::addFlash("error", "Une erreur est survenue veuillez recommencer");
                    return [
                        "view" => VIEW_DIR . "forum/published.php",
                        "edit" => false,
                        "meta_description" => "Ajouter un Article : ",
                        "data" => []
                    ];
                }
            } else {
                Session::addFlash("error", "Oups!, un problème est servenu");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("error", "L'identifiant de session n'est pas reconu, veuillez recommencer");
            $this->redirectTo("forum", "showFullTopic", $_GET['id']);
        }
    }

    /**
     * Affiche le contenu d'un topic et ces posts associés
     *
     * @return le topic et tous ces posts
     */
    public function showFullTopic()
    {
        if (isset($_GET['id'])) {
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            return [
                "view" => VIEW_DIR . "forum/topic.php",
                "post" => true,
                "meta_description" => "Ajouter un Article : ",
                "data" => [
                    "topic" => $topicManager->findOneByIdTopic($_GET['id']),
                    "posts" => $postManager->findAllByIdTopic($_GET['id'])
                ]
            ];
        } else {
            Session::addFlash("error", "Oups!, un problème est servenu");
            $this->redirectTo("home", "index");
        }
    }
    /**
     * éditer un post
     */
    public function editPost()
    {
        //XSCF
        if (isset($_POST['token-form-link']) && $_POST['token-form-link'] === $_SESSION['token']) {
            if (isset($_GET['id'])) {
                $postManager = new PostManager();
                $post = $postManager->findOneById($_GET['id']);
                return [
                    "view" => VIEW_DIR . "forum/topic.php",
                    "edit" =>  true,
                    "meta_description" => "Modifier un Article : ",
                    "data" => [
                        "topic" => $post->getTopic(),
                        "posts" => $postManager->findAllByIdTopic($post->getTopic()->getId())
                    ]
                ];
            } else {
                Session::addFlash("error", "Oups!, un problème est servenu");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("error", "L'identifiant de session n'est pas reconu, veuillez recommencer");
            $this->redirectTo("forum", "showFullTopic", $_GET['id']);
        }
    }
    /**
     * mise à jour d'un post
     */
    public function updatePost()
    {
        //XSCF
        if (isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {

            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($content && isset($_GET['id'])) {

                $postManager = new PostManager();
                $post = $postManager->findOneById($_GET['id']);

                if ($postManager->updatePost($_GET['id'], $content)) {
                    Session::addFlash("success", "Post mis à jour");
                    $this->redirectTo("forum", "showFullTopic", $post->getTopic()->getId(), "card-" . $_GET['id'] . "");
                } else {

                    Session::addFlash("error", "Une erreur lors de l'enregistrement en base de données");

                    $topicManager = new TopicManager();
                    return [
                        "view" => VIEW_DIR . "forum/topic.php",
                        "edit" =>  false,
                        "meta_description" => "Modifier un Article : ",
                        "data" => [
                            "topic" => $topicManager->findOneByIdTopic($post->getTopic()->getId()),
                            "posts" => $postManager->findAllByIdTopic($post->getTopic()->getId())
                        ]
                    ];
                }
            } else {
                Session::addFlash("error", "Oups!, un problème est servenu");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("error", "L'identifiant de session n'est pas reconu, veuillez recommencer");
            $this->redirectTo("forum", "showFullTopic", $_GET['id']);
        }
    }

    /**
     * Ajouter un Post
     */
    public function replyTopic()
    {
        // XSCF
        if (isset($_POST['token-hidden']) && $_POST['token-hidden'] === $_SESSION['token']) {
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
            if ($content && isset($_GET['id'])) {
                $topicManager = new TopicManager();
                $postManager = new PostManager();

                $date = new DateTime();
                $id_post = $postManager->add([
                    "content" => $content,
                    "dateCreation" => $date->format('Y-m-d H:i:s'),
                    "topic_id" => $_GET['id'],
                    "user_id" => Session::getUser()->getId()
                ]);
                if ($id_post) {
                    $this->redirectTo("forum", "showFullTopic", $_GET['id'], "card-" . $id_post . "");
                } else {
                    Session::addFlash("error", "Une erreur est survenue veuillez recommencer");
                    return [
                        "view" => VIEW_DIR . "forum/topic.php",
                        "edit" =>  true,
                        "meta_description" => "Ajouter un Article : ",
                        "data" => [
                            "topic" => $topicManager->findOneByIdTopic($_GET['id']),
                            "posts" => $postManager->findAllByIdTopic($_GET['id'])
                        ]
                    ];
                }
            } else {
                Session::addFlash("error", "Oups!, un problème est servenu");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("error", "L'identifiant de session n'est pas reconu, veuillez recommencer");
            $this->redirectTo("forum", "showFullTopic", $_GET['id']);
        }
    }
    /**
     * delete post
     * */
    public function deletePost()
    {
        if (isset($_POST['token-form-link']) && $_POST['token-form-link'] === $_SESSION['token']) {
            if (isset($_GET['id'])) {
                $postManager = new PostManager();
                $post = $postManager->findOneById($_GET['id']);
                if ($postManager->delete($post->getId())) {
                    Session::addFlash("success", "Post supprimé");
                    $this->redirectTo("forum", "showFullTopic", $post->getTopic()->getId(), "card-" . $_GET['id'] . "");
                } else {
                    Session::addFlash("error", "Une erreur est survenue veuillez recommencer");
                    $topicManager = new TopicManager();
                    return [
                        "view" => VIEW_DIR . "forum/topic.php",
                        "section" => "edit-topic",
                        "meta_description" => "Ajouter un Article : ",
                        "data" => [
                            "topic" => $topicManager->findOneByIdTopic(),
                            "posts" => $postManager->findAllByIdTopic($post->getTopic()->getId())
                        ]
                    ];
                }
            } else {
                Session::addFlash("error", "Oups!, un problème est servenu");
                $this->redirectTo("home", "index");
            }
        } else {
            Session::addFlash("error", "L'identifiant de session n'est pas reconu, veuillez recommencer");
            $this->redirectTo("forum", "showFullTopic", $_GET['id']);
        }
    }
}
