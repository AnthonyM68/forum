<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class UserManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\User";
    protected $tableName = "user";

    public function __construct(){
        parent::connect();
    }
    /**
     * Return email if exist
     *
     * @param [type] $email
     * @return void
     */
    public function searchIfEmailExist($email)
    {
        $sql = "SELECT email 
        FROM " . $this->tableName . " t
        WHERE t.email = :email";
        
        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false), 
            $this->className
        );
    }
    /**
     * return username if exist
     *
     * @param [type] $email
     * @return void
     */
    public function searchIfUsernamelExist($username)
    {
        $sql = "SELECT username 
        FROM " . $this->tableName . " t
        WHERE t.username = :username";
        
        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['username' => $username], false), 
            $this->className
        );
    }
  
}