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
    /**
     * Counter topics
     *
     * @return int
     */
    public function countTopics()
    {
        $sql = "SELECT t.*
        FROM " . $this->tableName . " t";
        // pour convertir le générateur en tableau
        $results = iterator_to_array($this->getMultipleResults(
            DAO::select($sql),
            $this->className
        ));

        return count($results);
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
    public function fullyInformationsNewsExperimentale($id_category)
    {
        $sql = "SELECT 
        t.id_topic,
        t.title AS topic_title,
        t.dateCreation AS topic_dateCreation,
        c.id_category,
        c.name AS category_name,
        u1.id_user AS topic_user_id,
        u1.username AS topic_username,
        p.id_post,
        p.content AS post_content,
        p.dateCreation AS post_ddateCreation,
        u2.id_user AS post_user_id,
        u2.username AS post_username
    FROM " . $this->tableName . " t
    JOIN 
        category c ON t.category_id = c.id_category
    JOIN 
        user u1 ON t.user_id = u1.id_user
    LEFT JOIN 
        post p ON t.id_topic = p.topic_id
    LEFT JOIN 
        user u2 ON p.user_id = u2.id_user
    WHERE
        t.category_id = :category_id
    ORDER BY
        t.id_topic, p.id_post;
    ";

        return $this->getMultipleResults(
            DAO::select($sql, ["category_id" => $id_category]),
            $this->className
        );
    }
}
