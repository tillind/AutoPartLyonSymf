<?php
/**
 * Created by PhpStorm.
 * User: rem63_000
 * Date: 13/01/2017
 * Time: 22:26
 */

namespace Employe\AutoPartBundle\Business;


class Voiture
{
    private $idVoiture;
    private $etatVoiture;
    private $datedebutassurance;
    private $datefinassurance;
    private $nbkilometre;
    private $numcartegrise;
    private $idstation;
    private $codetypevoiture;
    private $nomVoiture;

    /**
     * Voiture constructor.
     * @param $idVoiture
     * @param $etatVoiture
     * @param $datedebutassurance
     * @param $datefinassurance
     * @param $nbkilometre
     * @param $numcartegrise
     * @param $idstation
     * @param $codetypevoiture
     * @param $nomVoiture
     */
    public function __construct($idVoiture, $etatVoiture, $datedebutassurance, $datefinassurance, $nbkilometre, $numcartegrise, $idstation, $codetypevoiture, $nomVoiture)
    {
        $this->idVoiture = $idVoiture;
        $this->etatVoiture = $etatVoiture;
        $this->datedebutassurance = $datedebutassurance;
        $this->datefinassurance = $datefinassurance;
        $this->nbkilometre = $nbkilometre;
        $this->numcartegrise = $numcartegrise;
        $this->idstation = $idstation;
        $this->codetypevoiture = $codetypevoiture;
        $this->nomVoiture = $nomVoiture;
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

    /**
     * @return mixed
     */
    public function getEtatVoiture()
    {
        return $this->etatVoiture;
    }

    /**
     * @param mixed $etatVoiture
     */
    public function setEtatVoiture($etatVoiture)
    {
        $this->etatVoiture = $etatVoiture;
    }

    /**
     * @return mixed
     */
    public function getDatedebutassurance()
    {
        return $this->datedebutassurance;
    }

    /**
     * @param mixed $datedebutassurance
     */
    public function setDatedebutassurance($datedebutassurance)
    {
        $this->datedebutassurance = $datedebutassurance;
    }

    /**
     * @return mixed
     */
    public function getDatefinassurance()
    {
        return $this->datefinassurance;
    }

    /**
     * @param mixed $datefinassurance
     */
    public function setDatefinassurance($datefinassurance)
    {
        $this->datefinassurance = $datefinassurance;
    }

    /**
     * @return mixed
     */
    public function getNbkilometre()
    {
        return $this->nbkilometre;
    }

    /**
     * @param mixed $nbkilometre
     */
    public function setNbkilometre($nbkilometre)
    {
        $this->nbkilometre = $nbkilometre;
    }

    /**
     * @return mixed
     */
    public function getNumcartegrise()
    {
        return $this->numcartegrise;
    }

    /**
     * @param mixed $numcartegrise
     */
    public function setNumcartegrise($numcartegrise)
    {
        $this->numcartegrise = $numcartegrise;
    }

    /**
     * @return mixed
     */
    public function getIdstation()
    {
        return $this->idstation;
    }

    /**
     * @param mixed $idstation
     */
    public function setIdstation($idstation)
    {
        $this->idstation = $idstation;
    }

    /**
     * @return mixed
     */
    public function getCodetypevoiture()
    {
        return $this->codetypevoiture;
    }

    /**
     * @param mixed $codetypevoiture
     */
    public function setCodetypevoiture($codetypevoiture)
    {
        $this->codetypevoiture = $codetypevoiture;
    }

    /**
     * @return mixed
     */
    public function getNomVoiture()
    {
        return $this->nomVoiture;
    }

    /**
     * @param mixed $nomVoiture
     */
    public function setNomVoiture($nomVoiture)
    {
        $this->nomVoiture = $nomVoiture;
    }


}


