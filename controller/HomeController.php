<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\UserManager;
use Model\Managers\TopicManager;

class HomeController extends AbstractController implements ControllerInterface
{

    public function index()
    {
        $topicManager = new TopicManager();
        $topics = $topicManager->findAllPostByIdTopic();
        var_dump($topics);
        return [
            "view" => VIEW_DIR."home.php",
            "section" => "home",
            "meta_description" => "Page d'accueil du forum", 
            "data" => [
                "categories" => $topics
            ]
        ];
    }

}
