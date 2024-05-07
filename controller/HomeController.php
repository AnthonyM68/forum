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
    /**
     * Home
     *
     * @return void
     */
    public function index()
    {
        $categoryManager = new CategoryManager();
        $topicManager = new TopicManager();
        $postManager = new PostManager();
        $userManager = new UserManager();
        return [
            "view" => VIEW_DIR . "home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "categoryData" => $categoryManager->findAll(["name", "ASC"]),
                "lastUser" =>  $userManager->findLatestUser()
            ],
            // Statistiques pour la page d'accueil
            "countUsers" => $userManager->countUser(),
            "countTopics" => $topicManager->countTopics(),
            "countPosts" => $postManager->countPosts(),
            "countCategories" => $categoryManager->countCategories()
        ];
    }
}
