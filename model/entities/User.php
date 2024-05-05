<?php

namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class User extends Entity
{

    private $id;
    private $username;
    private $password;
    private $email;
    private $token;
    private $tokenValidity;
    private $dateRegister;
    private $role;


    public function __construct($data)
    {
        $this->hydrate($data);
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of nickName
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Set the value of nickName
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    /**
     * Get the value of dataRegister
     */
    public function getDateRegister()
    {
        return $this->dateRegister;
    }
    /**
     * Set the value of dataRegister
     *
     * @return  self
     */
    public function setDateRegister($dateRegister)
    {
        $this->dateRegister = $dateRegister;
        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    /**
     * Set the value of token validity
     *
     * @return  self
     */
    public function getTokenValidity()
    {
        return $this->tokenValidity;
    }
    /**
     * Set the value of token validity
     *
     * @return  self
     */
    public function setTokenValidity($tokenValidity)
    {
        $this->tokenValidity = $tokenValidity;
        return $this;
    }
    /**
     * Get the value of role
     */
    public function getRole()
    {
        return json_decode($this->role);
    }
    /**
     * Set the value of role
     *
     * @return  self
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    /**
     * on vérifie si l'utilisateur possède un rôle
     * 
     *
     * @param [type] $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (!empty($this->role)) {
            $role = $this->role;
            if (!is_iterable($role)) {
                $role = json_decode($this->role);
            }
            foreach ($role as $role) {
                if (strcmp($role, $role[0])) return true;
            }
        }
        return false;
    }

    public function __toString()
    {
        return $this->username;
    }
}
