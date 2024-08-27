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
                "categoryData" => false, //$categoryManager->findAll(["name", "ASC"]),
                "lastUser" =>  false //$userManager->findLatestUser()
            ],
            // Statistiques pour la page d'accueil
            "countUsers" => false, //$userManager->countUser(),
            "countTopics" => false, //$topicManager->countTopics(),
            "countPosts" => false, //$postManager->countPosts(),
            "countCategories" => false // $categoryManager->countCategories()
        ];
    }




    #[Route('/programme', name: 'programme', methods: ['GET'])]
 
    public function index(
       
        ProgrammeRepository $repository
       
    ): Response {
 
        $programmes = $repository->findAll();
 
        return $this->render('/pages/programme/index.html.twig', [
 
            'programmes' => $programmes
        ]);
    }
}
}
