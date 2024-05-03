<?php

namespace Model\Managers;

use App\Manager;
use App\DAO;

use DateTime;
class UserManager extends Manager
{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\User";
    protected $tableName = "user";

    public function __construct()
    {
        parent::connect();
    }
    /**
     * Counter users
     *
     * @return void
     */
    public function countUser() 
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
    
    
    /**
     * Return email if exist
     *
     * @param [type] $email
     * @return void
     */
    public function searchIfEmailExist($email)
    {
        $sql = "SELECT
        t.email
        FROM " . $this->tableName . " t
        WHERE t.email = :email";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false),
            $this->className
        );
    }
        /**
     * Return email if exist
     *
     * @param [type] $email
     * @return void
     */
    public function searchPasswordByEmail($email)
    {
        $sql = "SELECT
        t.password
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
     * @param [type] $username
     * @return void
     */
    public function searchIfUsernamelExist($username)
    {
        $sql = "SELECT 
        username 
        FROM " . $this->tableName . " t
        WHERE t.username = :username";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['username' => $username], false),
            $this->className
        );
    }
    /**
     * return token if exist
     *
     * @param [type] $token
     * @return void
     */
    public function searchIfTokenlExist($token)
    {
        $sql = "SELECT 
        t.id_user,
        t.email,
        t.token 
        FROM " . $this->tableName . " t
        WHERE t.token = :token";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['token' => $token], false),
            $this->className
        );
    }
    public function resetToken($token)
    {
        $updateSql = "UPDATE 
        " . $this->tableName . " t 
        SET t.token = NULL 
        WHERE t.token = :token";
        return DAO::update($updateSql, ['token' => $token]);
    }
    public function updateRoleUser($role, $id)
    {
        $updateSql = "UPDATE 
        " . $this->tableName . " t
        SET t.role = :role 
        WHERE t.id_user = :id_user";
        return DAO::update($updateSql, ['role' => $role, "id_user" => $id]);
    }
    public function infosUserConnectSession($email)
    {
        $sql = "SELECT
        t.username,
        t.role,
        t.id_user
        FROM " . $this->tableName . " t
        WHERE t.email = :email";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false),
            $this->className
        );
    }
    public function infoWithoutPassword($id)
    {
        $sql = "SELECT
        t.username,
        t.role,
        t.email
        FROM " . $this->tableName . " t
        WHERE t.id_user = :id";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ["id" => $id], false),
            $this->className
        );
    }
    
}
