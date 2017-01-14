<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 13/12/2016
 * Time: 17:07
 */

namespace Client\AutoPartBundle\Business;

class Voiture {
    private $idVoiture;
    private $etatVoiture;
    private $nomVoiture;
    private $idStation;
    private $type;

    public function __construct($id,$nom,$etat,$station,$type)
    {
        $this->idVoiture = $id;
        $this->nomVoiture = $nom;
        $this->etatVoiture = $etat;
        $this->idStation=$station;
        $this->type=$type;
    }

    /**
     * @return mixed
     */
    public function getEtatVoiture()
    {
        return $this->etatVoiture;
    }

    /**
     * @return mixed
     */
    public function getIdStation()
    {
        return $this->idStation;
    }

    /**
     * @return mixed
     */
    public function getIdVoiture()
    {
        return $this->idVoiture;
    }

    /**
     * @return mixed
     */
    public function getNomVoiture()
    {
        return $this->nomVoiture;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $etatVoiture
     */
    public function setEtatVoiture($etatVoiture)
    {
        $this->etatVoiture = $etatVoiture;
    }

    /**
     * @param mixed $idStation
     */
    public function setIdStation($idStation)
    {
        $this->idStation = $idStation;
    }

    /**
     * @param mixed $idVoiture
     */
    public function setIdVoiture($idVoiture)
    {
        $this->idVoiture = $idVoiture;
    }

    /**
     * @param mixed $nomVoiture
     */
    public function setNomVoiture($nomVoiture)
    {
        $this->nomVoiture = $nomVoiture;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}