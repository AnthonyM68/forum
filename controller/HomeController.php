<?php

namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\UserManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;

class HomeController extends AbstractController implements ControllerInterface
{
    public function index()
    {
        // les instanciations
        $categoryManager = new CategoryManager();
        $topicManager = new TopicManager();
        $postManager = new PostManager();
        $userManager = new UserManager();
        $result = [];
        // on recherche toutes les catégories
        $categories = $categoryManager->findAll();
        //on parcours toutes les catégories
        foreach ($categories as $category) {
            // on get le nom de catégorie
            $categoryName = $category->getName();
            // on get son Id et recherchons tous les posts associés
            $topics = $topicManager->findTopicsByCategory($category->getId());
            // on initialise un tableau vide
            $categoryData = [];
            // s'il existe des topics dans la catégorie
            if ($topics) {
                // on parcours tous les topics
                foreach ($topics as $topic) {
                    // on recherche tous les posts associés aux topics
                    $posts = $postManager->findAllByIdTopic($topic->getId());
                    /**
                     * on stock les résultats dans un nouveau tableau
                     * Clé nom d'Entité
                     * Value collection objets
                     */
                    $topicData = [
                        'topic' => $topic,
                        'posts' => $posts
                    ];
                    // on pousse notre tableau $topicData, dans le tableau de données
                    // par => Catégorie
                    $categoryData[] = $topicData;
                }
                // on pousse notre tableau $categoryData, dans le tableau principale
                // que l'on vas rendre a la vue
                $result[$categoryName] = $categoryData;
            }   
        }
        return [
            "view" => VIEW_DIR . "home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "categoryData" => $result
            ],
            // Statistiques pour la page d'accueil
            "countUsers" => $userManager->countUser(),
            "countTopics" => $topicManager->countTopics(),
            "countPosts" => $postManager->countPosts(),
            "countCategories" => $categoryManager->countCategories()
        ];
    }
}
