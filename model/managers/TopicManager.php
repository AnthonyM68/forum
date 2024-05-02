<?php

namespace Model\Managers;

use App\Manager;
use App\DAO;

class TopicManager extends Manager
{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Topic";
    protected $tableName = "topic";

    public function __construct()
    {
        parent::connect();
    }

    // récupérer tous les topics d'une catégorie spécifique (par son id)
    public function findTopicsByCategory($id)
    {

        $sql = "SELECT * 
                FROM " . $this->tableName . " t 
                WHERE t.category_id = :id";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]),
            $this->className
        );
    }
    public function findLast5Topics()
    {

        $sql = "SELECT * 
                FROM " . $this->tableName . " t
                ORDER BY t.dateCreation DESC
                LIMIT 5";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }
    public function findAllId($foreign)
    {

        $sql = "SELECT " . $foreign . "
                FROM " . $this->tableName . "";

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }
        /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function findAllPostByIdTopic()
    {
        $sql = "SELECT t.*,
                p.*
                FROM " . $this->tableName . " t
                LEFT JOIN post p ON post.topic_id = t.id_topic";

        return $this->getMultipleResults(
            DAO::select($sql, []),
            $this->className
        );
    }

}
