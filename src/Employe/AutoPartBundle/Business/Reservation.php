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
    private $datedebutreservation;
    private $datefinreservation;
    private $nbkilometreparcouru;
    private $idStationPartir;
    private $idStationArriver;
    private $idMembre;
    private $idVoiture;

    /**
     * Reservation constructor.
     * @param $idReservation
     * @param $etatReservation
     * @param $datedebutreservation
     * @param $datefinreservation
     * @param $nbkilometreparcouru
     * @param $idStationPartir
     * @param $idStationArriver
     * @param $idMembre
     * @param $idVoiture
     */
    public function __construct($idReservation, $etatReservation, $datedebutreservation, $datefinreservation, $nbkilometreparcouru, $idStationPartir, $idStationArriver, $idMembre, $idVoiture)
    {
        $this->idReservation = $idReservation;
        $this->etatReservation = $etatReservation;
        $this->datedebutreservation = $datedebutreservation;
        $this->datefinreservation = $datefinreservation;
        $this->nbkilometreparcouru = $nbkilometreparcouru;
        $this->idStationPartir = $idStationPartir;
        $this->idStationArriver = $idStationArriver;
        $this->idMembre = $idMembre;
        $this->idVoiture = $idVoiture;
    }

    /**
     * @return mixed
     */
    public function getIdReservation()
    {
        return $this->idReservation;
    }

    /**
     * @param mixed $idReservation
     */
    public function setIdReservation($idReservation)
    {
        $this->idReservation = $idReservation;
    }

    /**
     * @return mixed
     */
    public function getEtatReservation()
    {
        return $this->etatReservation;
    }

    /**
     * @param mixed $etatReservation
     */
    public function setEtatReservation($etatReservation)
    {
        $this->etatReservation = $etatReservation;
    }

    /**
     * @return mixed
     */
    public function getDatedebutreservation()
    {
        return $this->datedebutreservation;
    }

    /**
     * @param mixed $datedebutreservation
     */
    public function setDatedebutreservation($datedebutreservation)
    {
        $this->datedebutreservation = $datedebutreservation;
    }

    /**
     * @return mixed
     */
    public function getDatefinreservation()
    {
        return $this->datefinreservation;
    }

    /**
     * @param mixed $datefinreservation
     */
    public function setDatefinreservation($datefinreservation)
    {
        $this->datefinreservation = $datefinreservation;
    }

    /**
     * @return mixed
     */
    public function getNbkilometreparcouru()
    {
        return $this->nbkilometreparcouru;
    }

    /**
     * @param mixed $nbkilometreparcouru
     */
    public function setNbkilometreparcouru($nbkilometreparcouru)
    {
        $this->nbkilometreparcouru = $nbkilometreparcouru;
    }

    /**
     * @return mixed
     */
    public function getIdStationPartir()
    {
        return $this->idStationPartir;
    }

    /**
     * @param mixed $idStationPartir
     */
    public function setIdStationPartir($idStationPartir)
    {
        $this->idStationPartir = $idStationPartir;
    }

    /**
     * @return mixed
     */
    public function getIdStationArriver()
    {
        return $this->idStationArriver;
    }

    /**
     * @param mixed $idStationArriver
     */
    public function setIdStationArriver($idStationArriver)
    {
        $this->idStationArriver = $idStationArriver;
    }

    /**
     * @return mixed
     */
    public function getIdMembre()
    {
        return $this->idMembre;
    }

    /**
     * @param mixed $idMembre
     */
    public function setIdMembre($idMembre)
    {
        $this->idMembre = $idMembre;
    }

    /**
     * @return mixed
     */
    public function getIdVoiture()
    {
        return $this->idVoiture;
    }

    /**
     * @param mixed $idVoiture
     */
    public function setIdVoiture($idVoiture)
    {
        $this->idVoiture = $idVoiture;
    }

}