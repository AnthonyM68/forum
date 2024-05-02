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
        $categoryManager = new CategoryManager();
        $topicManager = new TopicManager();
        $postManager = new PostManager();
        $result = [];

        $categories = $categoryManager->findAll();

        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $topics = $topicManager->findTopicsByCategory($category->getId());
            $categoryData = [];
            if ($topics) {
                foreach ($topics as $topic) {
                    $posts = $postManager->findAllByIdTopic($topic->getId());
                    $topicData = [
                        'topic' => $topic,
                        'posts' => $posts
                    ];
                    $categoryData[] = $topicData;
                }
                $result[$categoryName] = $categoryData;
            }   
        }
        return [
            "view" => VIEW_DIR . "home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "categoryData" => $result
            ]
        ];
    }
}
