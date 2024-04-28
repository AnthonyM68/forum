<?php

namespace Model\Managers;

use App\Manager;
use App\DAO;

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
     * @param [type] $username
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
        t.token 
        FROM " . $this->tableName . " t
        WHERE t.token = :token";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        $result =  $this->getOneOrNullResult(
            DAO::select($sql, ['token' => $token], false),
            $this->className
        );
        if ($result && !empty($result->getToken())) {

            $deleteSql = "UPDATE " . $this->tableName . " t 
                      SET t.token = NULL 
                      WHERE t.token = :token";
            $deleteSql = DAO::update($deleteSql, ['token' => $token]);
            if ($deleteSql) {
                return $result->getId();
            }
        }
        return false;
    }
    public function updateRoleUser($role, $id)
    {
        $sql = "UPDATE role 
        FROM " . $this->tableName . " t
        WHERE t.token = :token";

        $deleteSql = "UPDATE " . $this->tableName . " t
                      SET t.role = :role 
                      WHERE t.id_user = :id_user";
        return DAO::update($deleteSql, [
            'role' => $role,
            "id_user" => $id
        ]);
    }
}
