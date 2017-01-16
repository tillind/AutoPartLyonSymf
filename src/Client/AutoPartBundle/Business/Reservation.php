<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 13/12/2016
 * Time: 17:07
 */

namespace Client\AutoPartBundle\Business;

class Reservation {
    private $idReservation;
    private $etatReservation;
    private $idEtatDesLieux;
    private $idStationPartir;
    private $idStationArriver;
    private $idMembre;
    private $idVoiture;

    public function __construct($idResa,$etat,$etatDesLieux,$stationP,$stationA,$membre,$voiture)
    {
        $this->idReservation = $idResa;
        $this->etatReservation = $etat;
        $this->idEtatDesLieux = $etatDesLieux;
        $this->idStationPartir = $stationP;
        $this->idStationArriver = $stationA;
        $this->idMembre = $membre;
        $this->idVoiture = $voiture;
    }

    /**
     * @return mixed
     */
    public function getIdReservation()
    {
        return $this->idReservation;
    }

    /**
     * @return mixed
     */
    public function getEtatReservation()
    {
        return $this->etatReservation;
    }

    /**
     * @return mixed
     */
    public function getIdEtatDesLieux()
    {
        return $this->idEtatDesLieux;
    }

    /**
     * @return mixed
     */
    public function getIdStationPartir()
    {
        return $this->idStationPartir;
    }

    /**
     * @return mixed
     */
    public function getIdStationArriver()
    {
        return $this->idStationArriver;
    }

    /**
     * @return mixed
     */
    public function getIdMembre()
    {
        return $this->idMembre;
    }

    /**
     * @return mixed
     */
    public function getIdVoiture()
    {
        return $this->idVoiture;
    }

    /**
     * @param mixed $idReservation
     */
    public function setIdReservation($idReservation)
    {
        $this->idReservation = $idReservation;
    }

    /**
     * @param mixed $etatReservation
     */
    public function setEtatReservation($etatReservation)
    {
        $this->etatReservation = $etatReservation;
    }

    /**
     * @param mixed $idEtatDesLieux
     */
    public function setIdEtatDesLieux($idEtatDesLieux)
    {
        $this->idEtatDesLieux = $idEtatDesLieux;
    }

    /**
     * @param mixed $idStationPartir
     */
    public function setIdStationPartir($idStationPartir)
    {
        $this->idStationPartir = $idStationPartir;
    }

    /**
     * @param mixed $idStationArriver
     */
    public function setIdStationArriver($idStationArriver)
    {
        $this->idStationArriver = $idStationArriver;
    }

    /**
     * @param mixed $idMembre
     */
    public function setIdMembre($idMembre)
    {
        $this->idMembre = $idMembre;
    }

    /**
     * @param mixed $idVoiture
     */
    public function setIdVoiture($idVoiture)
    {
        $this->idVoiture = $idVoiture;
    }


}