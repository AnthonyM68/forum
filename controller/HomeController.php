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
            $topics = $topicManager->findTopicsByCategory($category->getId());
            $nameCategory = $category->getName();

            $result[$nameCategory][] = (object)["topics" => $topics];



            if($topics) {
                foreach($topics as $topic) {
                    $posts = $postManager->findAllByIdTopic($topic->getId());
                    $result[$nameCategory][] = (object)["posts" => $posts];
                }
            } 
        }
        return [
            "view" => VIEW_DIR . "home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "results" => $result
            ]
        ];
    }

}
