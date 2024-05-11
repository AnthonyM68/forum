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
    /**
     * Counter posts
     *
     * @return int
     */
    public function countPosts()
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
    // renvoie une liste d'Id 
    public function findAllIdPostByIdTopic($id)
    {
        $sql = "SELECT 
        t.id_post,
        t.topic_id
        FROM " . $this->tableName . " t
        WHERE t.topic_id = :id";

        return $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]),
            $this->className
        );
    }
    /**
     * REcherche les 5 derniers Post
     *
     * @return void
     */
    public function findLast5Posts()
    {
        $sql = "SELECT 
                t.id_post,
                t.content,
                DATE_FORMAT(t.dateCreation, '%d/%m/%Y %H:%i:%s') AS dateCreation,
                t.topic_id,
                t.user_id
                FROM " . $this->tableName . " t
                ORDER BY dateCreation DESC
                LIMIT 5";

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }

    /**
     * Recherche les 5 derniers Post d'un topic
     *
     * @return void
     */
    public function findLast5PostsByTopic($id)
    {
        $sql = "SELECT 
                t.id_post,
                t.content,
                DATE_FORMAT(t.dateCreation, '%d/%m/%Y %H:%i:%s') AS dateCreation,
                t.topic_id,
                t.user_id
                FROM " . $this->tableName . " t
                WHERE t.topic_id = :topic_id
                ORDER BY dateCreation DESC
                LIMIT 5";

        return $this->getMultipleResults(
            DAO::select($sql, ['topic_id' => $id]),
            $this->className
        );
    }
    /**
     * Regroupe tous les posts d'un topic
     **/
    public function findAllByIdTopic($id)
    {
        $sql = "SELECT 
        t.id_post,
        t.content,
        DATE_FORMAT(t.dateCreation, '%d/%m/%Y %H:%i:%s') AS dateCreation,
        t.topic_id,
        t.user_id
        FROM " . $this->tableName . " t
        WHERE t.topic_id = :topic_id";
        //return DAO::select($sql, ['topic_id' => $id]);
        return $this->getMultipleResults(
            DAO::select($sql, ['topic_id' => $id]),
            $this->className
        );
    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function updatePost($id, $content)
    {
        $sql = "UPDATE " . $this->tableName . "
        SET content = :content
        WHERE id_" . $this->tableName . " = :id_post";
        return DAO::update($sql, [
            'id_post' => $id,
            'content' => $content
        ]);
    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    /* public function deleteByTopicId($id)
    {
        $sql = "DELETE 
                FROM " . $this->tableName . " t
                WHERE t.topic_id = :topic_id
                ";
        return DAO::delete($sql, ['topic_id' => $id]);
    }*/
}
