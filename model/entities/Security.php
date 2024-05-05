<?php

namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class Security extends Entity
{

    private $id;
    private $encryptedData;
    private $iv;
    private $user;
    private $token;
    private $tokenValidity;

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
     * Get the value of encrypted_data
     */
    public function getEncryptedData()
    {
        return $this->encryptedData;
    }
    /**
     * Set the value of encrypted_data
     *
     * @return  self
     */
    public function setEncryptedData($encryptedData)
    {
        $this->encryptedData = $encryptedData;
        return $this;
    }
    /**
     * Get the value of iv
     */
    public function getIv()
    {
        return $this->iv;
    }
    /**
     * Set the value of iv
     *
     * @return  self
     */
    public function setIv($iv)
    {
        $this->iv = $iv;
        return $this;
    }
    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
        /**
     * Get the value of token 
     */
    public function getToken()
    {
        return $this->token;
    }
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
    public function __toString()
    {
        return $this->user;
    }
}
