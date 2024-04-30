<?php

namespace Model\Managers;

use App\Manager;
use App\DAO;

class PostManager extends Manager
{
    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Post";
    protected $tableName = "post";

    public function __construct()
    {
        parent::connect();
    }
    public function findLast5Posts()
    {

        $sql = "SELECT * 
        FROM " . $this->tableName . " t
        ORDER BY t.dateCreation DESC
        LIMIT 5";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return  $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }
    public function deleteByTopicId($id)
    {
        $sql = "DELETE 
                FROM " . $this->tableName . " t
                WHERE t.topic_id = :topic_id
                ";
        return DAO::delete($sql, ['topic_id' => $id]);
    }
    public function findAllPostByIdTopicLIMIT($id)
    {
        $sql = "SELECT t.*
                FROM " . $this->tableName . " t
                WHERE t.topic_id = :topic_id";

                return $this->getMultipleResults(
                    DAO::select($sql, ['topic_id' => $id]), 
                    $this->className
                );
        
    }
    
}
