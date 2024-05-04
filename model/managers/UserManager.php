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
     * @return int 
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
     * Return user by id
     *
     * @param [type] $id
     * @return void
     */
    public function searchForDeleteAccount($id)
    {
        $sql = "SELECT
        t.id_user,
        t.password,
        t.email
        FROM " . $this->tableName . " t
        WHERE t.id_user = :id";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['id' => $id], false),
            $this->className
        );
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
     * Return password if exist by email
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
        t.username,
        t.password,
        t.email,
        t.dateRegister,
        t.role,
        t.token,
        t.token_validity
        FROM " . $this->tableName . " t
        WHERE t.token = :token";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['token' => $token], false),
            $this->className
        );
    }
    public function updateToken($token, $id)
    {
        $updateSql = "UPDATE 
        " . $this->tableName . " t 
        SET t.token = :token 
        WHERE t.id_user = :id_user";
        return DAO::update($updateSql, [
            'id_user' => $id,
            'token' => $token
        ]);
    }
    public function resetToken($token)
    {
        $resetSql = "UPDATE 
        " . $this->tableName . " t 
        SET t.token = NULL 
        WHERE t.token = :token";
        return DAO::update($resetSql, ['token' => $token]);
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
        t.id_user,
        t.username,
        t.role
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
        t.id_user,
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
    public function updateDataHashed($data)
    {
        // en cours 

        
        /*$keys = array_keys($data);
        $values = array_values($data);
        $set = [];
        foreach ($keys as $key) {
            if($key !== "id_user") {
                $set[] = "$key = :$key";
            }
        }

        $sql = "UPDATE 
        " . $this->tableName . " t
        SET " . $setString . "
        WHERE t.id_user = :id_user";

        return $this->getOneOrNullResult(
            DAO::update($sql, [
                "username " => $data['username'],
                "password " => $data['password'],
                "email " => $data['email'],
                "token " => $data['token'],
                "dateRegister " => $data['dateRegister'],
                "role " => $data['role'],
                "token_validity " => $data['token_validity'],
                "id_user" => $data['id_user']
            ]),
            $this->className
        );*/
    }
}
