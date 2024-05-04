<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class SecurityManager extends Manager
{
    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Category";
    protected $tableName = "datas_encrypted";

    public function __construct()
    {
        parent::connect();
    }
    public function addDataEncrypted($encryptedData, $iv, $id)
    {
        return $this->add([
            "encrypted_data" => $encryptedData,
            "iv" => $iv,
            "user_id" => $id
        ]);
    }
}