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
    
    /*Renvoie un tableau contenant toutes les voitures
    */
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

    /*Renvoie toutes les voitures d'une catégorie
    */
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

    /*Renvoie toutes les catégories de voitures sous la forme [libelle,code]
    */
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


    //Récupère voitures disponibles dans l'intervalle debut-fin
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
        //mise en forme des dates de résa choisies
        $newDate = str_replace('/', '-', $date);
        $date1 = DateTime::createFromFormat("d-m-Y H:i",$newDate);
        $newFin = str_replace('/', '-', $fin);
        $date2 = DateTime::createFromFormat("d-m-Y H:i",$newFin);

        if ($date1 <= $date2){
            /*Recuperation de toutes les interventions de cette voiture*/
            $query="SELECT dateIntervention, dateFinIntervention FROM InterventionVoiture WHERE idVoiture= :id";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(":id",$id,\PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt as $uneInter){
                /*Si une intervention chevauche les dates en paramètre, la voiture n'est pas dispo*/
                $newDebutInter = str_replace('/', '-', $uneInter[0]);
                $date3 = DateTime::createFromFormat("Y-m-d",$newDebutInter);
                $newFinInter = str_replace('/', '-', $uneInter[1]);
                $date4 = DateTime::createFromFormat("Y-m-d",$newFinInter);
                if ($this->checkDate($date1, $date2, $date3, $date4)==false){
                    return (false);
                }
            }

            /*Indentique avec les réservations*/ 
            $query2="SELECT dateDebutReservation, dateFinReservation FROM Reservation WHERE idVoiture= :id";
            $stmt2 = $this->connexion->prepare($query2);
            $stmt2->bindParam(":id",$id,\PDO::PARAM_STR);
            $stmt2->execute();
            foreach($stmt2 as $uneResa){
                $newDebutResa = str_replace('/', '-', $uneResa[0]);
                $date3 = DateTime::createFromFormat("Y-m-d H:i:s",$newDebutResa);
                $newFinResa = str_replace('/', '-', $uneResa[1]);
                $date4 = DateTime::createFromFormat("Y-m-d H:i:s",$newFinResa);
                if ($this->checkDate($date1, $date2, $date3, $date4)==false){
                    return (false);
                }
            }
            return $available;
        }
        //si début >= fin
        return false;
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
     
    //echo $date->format('Y-m-d');
    //echo $debut->format('Y-m-d');
    //echo $fin->format('Y-m-d');
    
    if ($date>=$debut and $date<=$fin){
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

            //$stmt = $this->connexion->prepare("SELECT idvoiture,etatreservation,idstationpartir,idstationarriver,datedebutreservation,datefinreservation,voiture.idvoiture,voiture.nomvoiture FROM reservation,voiture WHERE idmembre= (SELECT membre.idmembre FROM membre where membre.login= :log) AND reservation.idvoiture= voiture.idvoiture");

            $stmt = $this->connexion->prepare("SELECT reservation.idreservation,reservation.idvoiture,etatreservation,reservation.idstationpartir,reservation.idstationarriver,datedebutreservation,datefinreservation,voiture.idvoiture,voiture.nomvoiture,reservation.idetatdeslieux,stationPartir.nom as nomStationP,stationArriver.nom as nomStationA FROM reservation,voiture,station as stationPartir, station as stationArriver WHERE idmembre= (SELECT membre.idmembre FROM membre where membre.login= :log) AND reservation.idvoiture= voiture.idvoiture AND reservation.idstationpartir = stationPartir.idstation AND reservation.idstationarriver = stationArriver.idstation");

            $stmt->bindParam(":log",$login,\PDO::PARAM_STR);
            $stmt->execute();
            $row = null;
            foreach ($stmt as $ligne){
                $row[] = $ligne;
            }
            return $row;
        }
    }

    /* Change l'etat "en cours" à "annuler"*/

    public function annulerReservation($idreservation){
        if($this->connexion instanceof \PDO) {
            $stmt = $this->connexion->prepare("UPDATE reservation SET etatreservation = 'annulee' WHERE reservation.idreservation= :res");
            $stmt->bindParam(":res", $idreservation, \PDO::PARAM_STR);
            $stmt->execute();


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
                $debut = DateTime::createFromFormat("Y-m-d H:i:s",$uneResa[0]);
                $fin = DateTime::createFromFormat("Y-m-d H:i:s",$uneResa[1]);
                while ($debut <= $fin){
                    $indisponibilite[]=$debut->format('Y-m-d');
                    $debut->add(new DateInterval('P1D'));
                }
            }
        }

        return $indisponibilite;
    }

    /*Retourne tous les stations sous la forme [nom-adresse, id]
    */
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

    /*Ajoute une réservation
    Retourne true si l'insertion s'est effectuée
    False sinon
    */
    public function addResa($debut,$fin,$nbKil,$depart,$arrivee,$login,$idvoiture){
        if($this->connexion instanceof \PDO){
            $id=$this->getIdMembre($login);
            if ($id !=null){
                //vérification que la voiture est disponible aux dates demandées
                $available=$this->checkVoiture($idvoiture,$debut,$fin);
                if ($available){
                    $query="INSERT INTO reservation (etatreservation, datedebutreservation,datefinreservation,nbkilometreparcouru,idetatdeslieux,idstationpartir,idstationarriver, idmembre, idvoiture) values('en cours',:deb,:fin,:nbKil,null,:depart,:arrivee,:id,:voiture)";
                    $stmt = $this->connexion->prepare($query);
                    $stmt->bindParam(":deb",$debut,\PDO::PARAM_STR);
                    $stmt->bindParam(":fin",$fin,\PDO::PARAM_STR);
                    $stmt->bindParam(":nbKil",$nbKil,\PDO::PARAM_STR);
                    $stmt->bindParam(":depart",$depart,\PDO::PARAM_STR);
                    $stmt->bindParam(":arrivee",$arrivee,\PDO::PARAM_STR);
                    $stmt->bindParam(":id",$id,\PDO::PARAM_STR);
                    $stmt->bindParam(":voiture",$idvoiture,\PDO::PARAM_STR);
                    $stmt->execute();
                    return true;
                }   
            }
        }
        return false;
    }

    /*Retourne l'id du membre dont le login est $login
    */
    private function getIdMembre ($login){
        if($this->connexion instanceof \PDO){
            $query="SELECT idmembre from membre where login=:login";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(":login",$login,\PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt as $membre){
                return $membre[0];
            }
        }
        return null;
    }

    /*Ajoute un état des lieux pour la réservation
    */
    public function addEtat ($idResa, $etatGen){
        $query="SELECT sp_insert_etat(:id, :etat)";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(":id",$idResa,\PDO::PARAM_STR);
        $stmt->bindParam(":etat",$etatGen,\PDO::PARAM_STR);
        $stmt->execute();
        $row  = $stmt -> fetch();
        return $row['sp_insert_etat'];
    }

}