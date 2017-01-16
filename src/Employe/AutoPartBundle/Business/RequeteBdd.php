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
    public function getDateAsString($var,$var2,$var3){
        $date=[];
        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT to_char($var,'DD-MM-YYYY'),to_char($var2,'DD-MM-YYYY')FROM voiture WHERE datedebutassurance= :var2");
            $stmt->bindParam(":var2",$var3);
            $stmt->execute();
            foreach($stmt as $laDate){
                $date= array('date'=>$laDate[0],'date2'=>$laDate[1]);
            }
        };


        return $date;
    }

    //Récupère voiture disponible dans l'intervalle debut-fin
    public function getLesVoitureByEtatAndCateg($etat, $categ){
        $lesVoitures =array();
        if($this->connexion instanceof \PDO){
            $query="SELECT idvoiture, etatvoiture, datedebutassurance, datefinassurance,nbkilometre, numcartegrise,  idstation, codetypevoiture,nomVoiture FROM voiture where codetypevoiture=:categ";
            if ($etat !=null){
                $query .=" and etatvoiture= :etat";
                $stmt = $this->connexion->prepare($query);
                $stmt->bindParam(":categ",$categ,\PDO::PARAM_STR);
                $stmt->bindParam(":etat",$etat,\PDO::PARAM_STR);
            }
            else{
                $stmt = $this->connexion->prepare($query);
                $stmt->bindParam(":categ",$categ,\PDO::PARAM_STR);
            }
            $stmt->execute();
            foreach($stmt as $uneVoiture){
                    $lesVoitures[]= new Voiture($uneVoiture[0],$uneVoiture[1],$uneVoiture[2],$uneVoiture[3],$uneVoiture[4],$uneVoiture[5],$uneVoiture[6],$uneVoiture[7],$uneVoiture[8]);
            }
        }


        return $lesVoitures;
    }

    public function getLesVoituresById($var){
        $lesVoiture =null;
        $lesStations=null;
        $result=null;
        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT idvoiture, etatvoiture, to_char(datedebutassurance,'DD-MM-YYYY'), to_char(datefinassurance,'DD-MM-YYYY'), nbkilometre, numcartegrise,  voiture.idstation, codetypevoiture, nomvoiture,station.nom FROM voiture INNER JOIN station ON voiture.idstation=station.idstation WHERE idvoiture= :var");
            $stmt->bindParam(":var",$var,\PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt as $lavoiture){
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[1],$lavoiture[2],$lavoiture[3],$lavoiture[4],$lavoiture[5],$lavoiture[6],$lavoiture[7],$lavoiture[8]);
                $lesStations[]=array('nom'=>$lavoiture[9],'id'=> $lavoiture[6]);
            }
        };
        return $result=array($lesVoiture,$lesStations);
    }
    public function ajoutVehicule($arrayVehicule){






       $stmt2=$this->connexion->prepare(" SELECT COUNT(*) FROM voiture WHERE idstation =:idstation");
        $stmt2->bindParam(":idstation",$arrayVehicule['idstation'],\PDO::PARAM_STR);
        $stmt2->execute();
        $nbvoitureactuelle= $stmt2 -> fetch();
        $stmt3=$this->connexion->prepare("SELECT capacite FROM station WHERE idstation =:idstation");
        $stmt3->bindParam(":idstation",$arrayVehicule['idstation'],\PDO::PARAM_STR);
        $stmt3->execute();
        $nbvoituretotale = $stmt3 -> fetch();

        if($nbvoitureactuelle['count']<$nbvoituretotale['capacite'])
        {
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

    }
    public function getLesCategVoiture(){
        $lesCategories =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT code, libelle FROM typevoiture")->fetchAll();
            foreach($stmt as $uneCateg){
                $lesCategories[$uneCateg[1]]= $uneCateg[0];
            }
        }else{
            //  echo"un probleme";die;
        }
        return $lesCategories;
    }

    public function getLesVoituresByCateg($var){
        $lesVoiture =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT idvoiture,etatvoiture,nomvoiture,idstation,libelle FROM voiture,typeVoiture WHERE codetypevoiture= :var and voiture.codetypevoiture=typevoiture.code");
            $stmt->bindParam(":var",$var,\PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt as $lavoiture){
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[2],$lavoiture[1],$lavoiture[3],$lavoiture[4]);
            }
        }else{
            //  echo"un probleme";die;
        }
        return $lesVoiture;
    }
    public function modifVehicule($arrayVehicule,$station){
        $stmt = $this->connexion->prepare("UPDATE public.voiture SET etatvoiture=:etat, datedebutassurance=:datedeb, datefinassurance=:datefin, nbkilometre=:nbkilo, numcartegrise=:numcartegrise, proprietaire=TRUE, idstation=:idstation, codetypevoiture=:codetypevoiture, nomvoiture=:nomvoiture WHERE idvoiture=:idvoiture;");
        $stmt->bindParam(':etat',$arrayVehicule['etatvoiture'],\PDO::PARAM_STR);
        $stmt->bindParam(":datedeb",$arrayVehicule['datedebutassurance'],\PDO::PARAM_STR);
        $stmt->bindParam(":datefin",$arrayVehicule['datefinassurance'],\PDO::PARAM_STR);
        $stmt->bindParam(":nbkilo",$arrayVehicule['nbkilometres'],\PDO::PARAM_STR);
        $stmt->bindParam(":numcartegrise",$arrayVehicule['numcartegrise'],\PDO::PARAM_STR);
        $stmt->bindParam(":idstation",$station,\PDO::PARAM_INT);
        $stmt->bindParam(":codetypevoiture",$arrayVehicule['codevoiture'],\PDO::PARAM_STR);
        $stmt->bindParam(":nomvoiture",$arrayVehicule['nomvehicule'],\PDO::PARAM_STR);
        $stmt->bindParam(":idvoiture",$arrayVehicule['idvehicule'],\PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deplacerVehicule($arrayStation,$idvoiture){
        $stmt = $this->connexion->prepare("SELECT public.deplacement_voiture(
            :idvoiture,
            :idstation
        );");
        $stmt->bindParam(":idvoiture",$idvoiture);
        $stmt->bindParam(":idstation",$arrayStation['idstation']);
        $stmt->execute();
        $row  = $stmt -> fetch();
        return $row['deplacement_voiture'];

    }
}
