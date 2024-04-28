<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class EmailManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Email";
    protected $tableName = "email";

    public function __construct(){
        parent::connect();
    }
}