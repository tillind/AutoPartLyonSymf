<?php
/**
 * Created by PhpStorm.
 * User: rem63_000
 * Date: 06/01/2017
 * Time: 18:39
 */

namespace Employe\AutoPartBundle\Business;


class RequeteBdd
{
    private $connexion = null;

    public function __construct(Connection $connect)
    {
        $this->connexion = $connect->getPdo();
    }
    public function getLesStations(){


        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT idstation,nom FROM station")->fetchAll();
            foreach($stmt as $lastation){
                $lesStations[]= array('id'=>$lastation[0],'nom'=>$lastation[1]);
            }
        }
        return $lesStations;
    }
    public function getLesTypesVoitures(){
        $lesTypesVoitures =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT code,libelle FROM typevoiture")->fetchAll();
            foreach($stmt as $leType){
                $lesTypesVoitures[]= array('code'=>$leType[0],'libelle'=>$leType[1]);
            }
        }
        return $lesTypesVoitures;
    }


    public function getLesVoituresById($var){
        $lesVoiture =null;
        $lesStations=null;
        $result=null;
        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT idvoiture, etatvoiture, datedebutassurance, datefinassurance, nbkilometre, numcartegrise,  voiture.idstation, codetypevoiture, nomvoiture,station.nom FROM voiture INNER JOIN station ON voiture.idstation=station.idstation WHERE idvoiture= :var");
            $stmt->bindParam(":var",$var,\PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt as $lavoiture){
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[1],$lavoiture[2],$lavoiture[3],$lavoiture[4],$lavoiture[5],$lavoiture[6],$lavoiture[7],$lavoiture[8]);
                $lesStations[]=array('nom'=>$lavoiture[9]);
            }
        };
        return $result=array($lesVoiture,$lesStations);
    }
    public function ajoutVehicule($arrayVehicule){
        $stmt = $this->connexion->prepare("INSERT INTO public.voiture(idvoiture, etatvoiture, datedebutassurance, datefinassurance,nbkilometre, numcartegrise, proprietaire, idstation, codetypevoiture,nomVoiture) VALUES(
            DEFAULT,
            :etat,
            :datedeb,
            :datefin,
            :nbkilo,
            :numcartegrise,
            TRUE,
            :idstation,
            :codetypevoiture,
            :nomvoiture
        );");

        $stmt->bindParam(":etat",$arrayVehicule['etatvoiture'],\PDO::PARAM_STR);
        $stmt->bindParam(":datedeb",$arrayVehicule['datedebutassurance'],\PDO::PARAM_STR);
        $stmt->bindParam(":datefin",$arrayVehicule['datefinassurance'],\PDO::PARAM_STR);
        $stmt->bindParam(":nbkilo",$arrayVehicule['nbkilometres'],\PDO::PARAM_STR);
        $stmt->bindParam(":numcartegrise",$arrayVehicule['numcartegrise'],\PDO::PARAM_STR);
        $stmt->bindParam(":idstation",$arrayVehicule['idstation'],\PDO::PARAM_STR);
        $stmt->bindParam(":codetypevoiture",$arrayVehicule['codevoiture'],\PDO::PARAM_STR);
        $stmt->bindParam(":nomvoiture",$arrayVehicule['nomvehicule'],\PDO::PARAM_STR);
        $stmt->execute();
        $row  = $stmt -> fetch();
    }
    public function getLesCategVoiture(){
        $lesCategories =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT code, libelle FROM typevoiture")->fetchAll();
            foreach($stmt as $uneCateg){
                $lesCategories[]= array("cat" =>$uneCateg[0],"lib"=>$uneCateg[1]);
            }
        }
        return $lesCategories;
    }

}
