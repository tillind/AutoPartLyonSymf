<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 13/12/2016
 * Time: 17:07
 */

namespace Base\AutoPartBundle\Business;

class Voiture {

    private $idVoiture;
    private $etatVoiture;
    private $nomVoiture;
    private $idStation;

    public function __construct($id,$nom,$etat,$station)
    {
        $this->idVoiture = $id;
        $this->nomVoiture = $nom;
        $this->etatVoiture = $etat;
        $this->idStation=$station;
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
}