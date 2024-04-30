<?php

namespace App;
use DateTime;
abstract class Entity
{

    protected function hydrate($data)
    {

        foreach ($data as $field => $value) {
            // field = topic_id
            // fieldarray = ['topic','id']
            $fieldArray = explode("_", $field);

            if (isset($fieldArray[1]) && $fieldArray[1] == "id") {
                // manName = TopicManager 
                $manName = ucfirst($fieldArray[0]) . "Manager";
                // FQCName = Model\Managers\TopicManager;
                $FQCName = "Model\Managers\\" . $manName;

                // man = new Model\Managers\TopicManager
                $man = new $FQCName();
                // value = Model\Managers\TopicManager->findOneById(1)
              
                $value = $man->findOneById($value);
            }
            /**
             * fabrication du nom du setter (nom de method) à appeler (ex: setName)
             */
            $method = "set" . ucfirst($fieldArray[0]);
            // si setName est une méthode qui existe dans l'entité (this)
            if (method_exists($this, $method)) {
                /**
                 * Ajouts des convertions
                 */
                // on utilise ici une condition pour convertir les données reçus
                if ($fieldArray[0] === 'role') {
                    // Si le champ est 'roles' on décode (format enregistré en bdd)
                    $this->$method(json_decode($value));
                }
                if ($fieldArray[0] === 'dateCreation') {
                    $dateTime = DateTime::createFromFormat("Y-m-d H:i:s", $value);
                    // on convertis les données Sql en un objet DateTime valide
                    $formattedDate = $dateTime->format("d/m/Y H:i:s");
                    $this->$method($formattedDate);
                } 
                else {
                    // $this->setName("valeur")
                    // Appel du setter avec la valeur appropriée
                    $this->$method($value);
                }
            }
        }
    }

    public function getClass()
    {
        return get_class($this);
    }
}
