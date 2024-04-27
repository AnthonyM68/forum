<?php
namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class Post extends Entity{

    private $id;
    private $content;
    private $creationDate;
    private $id_topic;

    public function __construct($data){         
        $this->hydrate($data);        
    }

    /**
     * Get the value of id
     */ 
    public function getId(){
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId_topic($id){
        $this->id_topic = $id;
        return $this;
    }
    /**
     * Get the value of id
     */ 
    public function getId_topic(){
        return $this->id_topic;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    /**
     * Get the value of content
     */ 
    public function getContent(){
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content){
        $this->content = $content;
        return $this;
    }

    /**
     * Get the value of creationDate
     */ 
    public function getCreationDate(){
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return  self
     */ 
    public function setCreationDate($creationDate){
        $this->creationDate = $creationDate;
        return $this;
    }

    public function __toString(){
        return $this->content;
    }
}