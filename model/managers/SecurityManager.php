<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class SecurityManager extends Manager
{
    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Security";
    protected $tableName = "datasEncrypted";

    public function __construct()
    {
        parent::connect();
    }
    public function addDataEncrypted($encryptedData, $iv, $id, $token, $tokenValidity = null )
    {
        return $this->add([
            "encryptedData" => $encryptedData,
            "iv" => $iv,
            "user_id" => $id,
            "token" => $token,
            "tokenValidity" => $tokenValidity
         ]);
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
        t.encryptedData,
        t.iv,
        t.user_id,
        t.token
        FROM " . $this->tableName . " t
        WHERE t.token = :token";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getOneOrNullResult(
            DAO::select($sql, ['token' => $token], false),
            $this->className
        );
    }
}