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
     * Recherche le dernier utilisateur inscrit
     *
     * @return void
     */
    public function findLatestUser()
    {
        $sql = "SELECT
            t.id_user,
            t.username,
            t.role,
            t.email,
            DATE_FORMAT(t.dateRegister, '%d/%m/%Y %H:%i:%s') AS dateRegister
            FROM " . $this->tableName . " t
            ORDER BY t.dateRegister DESC
            LIMIT 1";
            return $this->getSingleScalarResult(
                DAO::select($sql),
                $this->className
            );
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
    public function dataUserPseudoAnonymsize($token)
    {
        $sql = "SELECT
        t.id_user,
        t.username,
        t.password,
        t.email,
        t.dateRegister,
        t.role
        FROM " . $this->tableName . " t
        WHERE t.token = :token";
        return $this->getOneOrNullResult(
            DAO::select($sql, ['token' => $token], false),
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
        t.tokenValidity
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
        $sql = "UPDATE 
        " . $this->tableName . " t 
        SET t.token = :token 
        WHERE t.id_user = :id_user";
        return DAO::update($sql, [
            'id_user' => $id,
            'token' => $token
        ]);
    }
    public function resetToken($token)
    {
        $sql = "UPDATE 
        " . $this->tableName . " t 
        SET t.token = NULL, t.tokenValidity = NULL
        WHERE t.token = :token ";
        return DAO::update($sql, ['token' => $token]);
    }
    public function updateRoleUser($role, $id)
    {
        $sql = "UPDATE 
        " . $this->tableName . " t
        SET t.role = :role 
        WHERE t.id_user = :id_user";
        return DAO::update($sql, ['role' => $role, "id_user" => $id]);
    }
    public function updateAfterRestaur($username, $password, $email, $id_user)
    {
        $sql = "UPDATE 
        " . $this->tableName . " t 
        SET t.username = :username, t.password = :password, t.email = :email 
        WHERE t.id_user = :id_user";
        return DAO::update($sql, [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'id_user' => $id_user
        ]);
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

        // on extrait le premier elem ($id)
        $id = array_shift($data);
        $keys = array_keys($data);
        $values = array_values($data);
        // on concat la chaine SET SQL
        $set = [];
        foreach ($keys as $key) {
            $set[] = "$key = :$key";
        }
        $setString = implode(', ', $set);
        // SQL
        $sql = "UPDATE
        " . $this->tableName . " t
        SET " . $setString . "
        WHERE t.id_user = :id_user";
        // on replace id_user a la fin du tableau de données
        $data["id_user"] = $id;

        return DAO::update($sql, $data);
    }
}
