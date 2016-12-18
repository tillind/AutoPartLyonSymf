<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 13/12/2016
 * Time: 17:07
 */

namespace Base\AutoPartBundle\Business;

class Voiture {
    private $id;
    private $nom;

    public function __construct($id,$nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }



    public function insertBdd(){

    }
}