<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 28/12/2016
 * Time: 11:06
 */


namespace Client\AutoPartBundle\Business;
use \DateTime;
use \DateInterval;


class RequeteBdd
{
    private $connexion = null;

    public function __construct(Connection $connect)
    {
           $this->connexion = $connect->getPdo();
    }
    
    public function getLesVoitures(){
        $lesVoiture =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT idvoiture,etatvoiture,nomvoiture,idstation, libelle FROM voiture, typeVoiture where voiture.codetypevoiture=typevoiture.code")->fetchAll();
            foreach($stmt as $lavoiture){
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[2],$lavoiture[1],$lavoiture[3],$lavoiture[4]);
            }
        }else{
          //  echo"un probleme";die;
        }
        return $lesVoiture;
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


    //Récupère voiture disponible dans l'intervalle debut-fin
    public function getLesVoitureByDateAndCateg($dateDebut, $dateFin, $categ){
        $lesVoitures =array();

        if($this->connexion instanceof \PDO){

            //si des dates ont été demandées
            if ($dateDebut!=null and $dateFin!=null){
                $query="SELECT idvoiture,etatvoiture,nomvoiture,idstation, libelle FROM voiture, typeVoiture where voiture.codetypevoiture=typevoiture.code";
                $stmt;
                if ($categ !=null){
                    $query .=" and codetypevoiture= :categ";
                    $stmt = $this->connexion->prepare($query);
                    $stmt->bindParam(":categ",$categ,\PDO::PARAM_STR);
                }
                else{
                    $stmt = $this->connexion->prepare($query);
                }
                $stmt->execute();
                foreach($stmt as $uneVoiture){
                    if ($this->checkVoiture($uneVoiture[0], $dateDebut, $dateFin)){
                        $lesVoitures[]= new Voiture($uneVoiture[0],$uneVoiture[2],$uneVoiture[1],$uneVoiture[3],$uneVoiture[4]);
                    }
                }
            }
        
            else{
                //si juste une catégorie demandée
                if ($categ !=null){
                    $lesVoitures=$this->getLesVoituresByCateg($categ);
                }
                //si aucun fitre de recherche
                else{
                    $lesVoitures=$this->getLesVoitures();
                }
            }
        }
        return $lesVoitures;
    }

    /*Retour: booleen
    Vrai si la voiture est dispo dans l'intervalle $date-$fin
    */
    private function checkVoiture($id, $date, $fin){
        $available=true;
        /*Recuperation de toutes les interventions de cette voiture*/
        $query="SELECT dateIntervention, dateFinIntervention FROM InterventionVoiture WHERE idVoiture= :id";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(":id",$id,\PDO::PARAM_STR);
        $stmt->execute();
        foreach($stmt as $uneInter){
            /*Si une intervention chevauche les dates en paramètre, la voiture n'est pas dispo*/ 
            if ($this->checkDate($date, $fin, $uneInter[0], $uneInter[1])==false){
                return (false);
            }
        }

        /*Indentique avec les réservations*/ 
        $query2="SELECT dateDebutReservation, dateFinReservation FROM Reservation WHERE idVoiture= :id";
        $stmt2 = $this->connexion->prepare($query2);
        $stmt2->bindParam(":id",$id,\PDO::PARAM_STR);
        $stmt2->execute();
        foreach($stmt2 as $uneResa){
            if ($this->checkDate($date, $fin, $uneResa[0], $uneResa[1])==false){
                return (false);
            }
        }
        return $available;
    }

    /* Retour: booleen
    Faux si les intervalles $debut1-$fin1 et $debut2-$fin2 se chevauchent
    Vrai sinon
    */
    private function checkDate($debut1, $fin1, $debut2, $fin2){
        if ($this->dateIn($debut1,$debut2,$fin2)
            or $this->dateIn($fin1,$debut2,$fin2)
            or $this->dateIn($debut2,$debut1,$fin1)
            or $this->dateIn($fin2,$debut1,$fin1)){
            return false;
        }
        else{
            return true;
        }
    }

    /* Retour: booleen
    Vrai si $date est dans l'intervalle $debut-$fin
    Faux sinon
     */ 
    private function dateIn($date, $debut, $fin){        

    $newDebut = str_replace('/', '-', $debut);
    $date2 = DateTime::createFromFormat("Y-m-d",$newDebut);

    $newFin = str_replace('/', '-', $fin);
    $date3 = DateTime::createFromFormat("Y-m-d",$newFin);

    $newDate = str_replace('/', '-', $date);
    $date1 = DateTime::createFromFormat('m-d-Y H:i',$newDate);


    if ($date1>=$date2 and $date1<=$date3){
        return (true);
    }
    else{
        return (false);
        }
    }


    public function getVoitureById($id){
        if($this->connexion instanceof \PDO){
            $query="SELECT idvoiture,etatvoiture,nomvoiture,idstation, libelle FROM voiture, typeVoiture where voiture.idvoiture=:id and voiture.codetypevoiture=typevoiture.code";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(":id",$id,\PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt as $uneVoiture){
                return (new Voiture($uneVoiture[0],$uneVoiture[2],$uneVoiture[1],$uneVoiture[3],$uneVoiture[4]));
            }
        }
        return null;
    }


    /* Recupere les reservations d'un client*/

    public function getReservation($login){
        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT idvoiture,etatreservation,idstationpartir,idstationarriver,datedebutreservation,datefinreservation,voiture.idvoiture,voiture.nomvoiture FROM reservation,voiture WHERE idmembre= (SELECT membre.idmembre FROM membre where membre.login= :log) AND reservation.idvoiture= voiture.idvoiture )";
            $stmt->bindParam(":log",$login,\PDO::PARAM_STR);
            $stmt->execute();
            $row = null;
            foreach ($stmt as $ligne){
                $row[] = $ligne;
            }
            return $row;
        }
    }

    /*Retourn un tableau contenant toutes les indisponibilités d'une voiture (réservation et intervention)
    */
    public function getIndispoById($id){
        $indisponibilite =array();

        if($this->connexion instanceof \PDO){
            $query="SELECT dateIntervention, dateFinIntervention FROM InterventionVoiture WHERE idVoiture= :id";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(":id",$id,\PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt as $uneInter){
                $debut = DateTime::createFromFormat("Y-m-d",$uneInter[0]);
                $fin = DateTime::createFromFormat("Y-m-d",$uneInter[1]);
                while ($debut <= $fin){
                    $indisponibilite[]=$debut->format('Y-m-d');
                    $debut->add(new DateInterval('P1D'));
                }
            }

            $query2="SELECT dateDebutReservation, dateFinReservation FROM Reservation WHERE idVoiture= :id";
            $stmt2 = $this->connexion->prepare($query2);
            $stmt2->bindParam(":id",$id,\PDO::PARAM_STR);
            $stmt2->execute();
            foreach($stmt2 as $uneResa){
                $debut = DateTime::createFromFormat("Y-m-d",$uneResa[0]);
                $fin = DateTime::createFromFormat("Y-m-d",$uneResa[1]);
                while ($debut <= $fin){
                    $indisponibilite[]=$debut->format('Y-m-d');
                    $debut->add(new DateInterval('P1D'));
                }
            }
        }

        return $indisponibilite;
    }


    public function getLesStations(){

        $lesStations =array();
        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT idstation,nom,adresse FROM station")->fetchAll();
            foreach($stmt as $lastation){
                $nomAdresse=$lastation[1]." - ".$lastation[2];
                $lesStations[$nomAdresse]= $lastation[0];
            }
        }
        return $lesStations;
    }

}