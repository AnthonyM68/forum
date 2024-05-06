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
            $topics = $topicManager->fullyInformationsNewsExperimentale($category->getId());
        }

        return [
            "view" => VIEW_DIR . "home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "categoryData" => $topics
            ],
            // Statistiques pour la page d'accueil
            "countUsers" => $userManager->countUser(),
            "countTopics" => $topicManager->countTopics(),
            "countPosts" => $postManager->countPosts(),
            "countCategories" => $categoryManager->countCategories()
        ];
    }
}
