<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class CategoryManager extends Manager
{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Category";
    protected $tableName = "category";

    public function __construct()
    {
        parent::connect();
    }
    /**
     * Counter category
     *
     * @return int
     */
    public function countCategories() 
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

}