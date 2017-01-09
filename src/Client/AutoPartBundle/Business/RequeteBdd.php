<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 28/12/2016
 * Time: 11:06
 */

namespace Client\AutoPartBundle\Business;


class RequeteBdd
{
    private $connexion = null;

    public function __construct(Connection $connect)
    {
           $this->connexion = $connect->getPdo();
    }

    public function userConnexion($login,$pwd){
        $tmpPass =crypt($pwd,password_hash($pwd, PASSWORD_DEFAULT));

        $stmt = $this->connexion->prepare("SELECT public.sp_verif_connect_membre(:log)");
        $stmt->bindParam(":log",$login,\PDO::PARAM_STR);
        $stmt->execute();
        $row  = $stmt -> fetch();

        if(hash_equals($row['sp_verif_connect_membre'], crypt($pwd,$row['sp_verif_connect_membre']))){
            return $this->getMembre($login);
        }else{
            return null;
        }

    }

    public function userInscription($arrayUser){
        $tmpPass =crypt($arrayUser['pass'],password_hash($arrayUser['pass'], PASSWORD_DEFAULT));
        $stmt = $this->connexion->prepare("SELECT public.sp_inscription_membre(
            :mail,
            :nom,
            :prenom,
            :log,
            :pass,
            :adr,
            :ddn,
            :tp,
            :tel
        );");

        $stmt->bindParam(":log",$arrayUser['login'],\PDO::PARAM_STR);
        $stmt->bindParam(":nom",$arrayUser['nom'],\PDO::PARAM_STR);
        $stmt->bindParam(":prenom",$arrayUser['prenom'],\PDO::PARAM_STR);
        $stmt->bindParam(":mail",$arrayUser['mail'],\PDO::PARAM_STR);
        $stmt->bindParam(":tel",$arrayUser['tel'],\PDO::PARAM_STR);
        $stmt->bindParam(":adr",$arrayUser['adresse'],\PDO::PARAM_STR);
        $stmt->bindParam(":ddn",$arrayUser['birth']);
        $stmt->bindParam(":tp",$arrayUser['permis'],\PDO::PARAM_STR);
        $stmt->bindParam(":pass",$tmpPass);



        $stmt->execute();
        $row  = $stmt -> fetch();
        return $row['sp_inscription_membre'];
    }

    public function getLesVoitures(){
        $lesVoiture =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT idvoiture,etatvoiture,nomvoiture,idstation FROM public.voiture")->fetchAll();
            foreach($stmt as $lavoiture){
                //
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[2],$lavoiture[1],$lavoiture[3]);
            }
        }else{
          //  echo"un probleme";die;
        }
        return $lesVoiture;
    }

    public function getLesVoituresByCateg($var){
        $lesVoiture =array();

        if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->prepare("SELECT idvoiture,etatvoiture,nomvoiture,idstation FROM voiture WHERE codetypevoiture= :var");
            $stmt->bindParam(":var",$var,\PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt as $lavoiture){
                $lesVoiture[]= new Voiture($lavoiture[0],$lavoiture[2],$lavoiture[1],$lavoiture[3]);
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
                $lesCategories[]= array("cat" =>$uneCateg[0],"lib"=>$uneCateg[1]);
            }
        }else{
            //  echo"un probleme";die;
        }
        return $lesCategories;
    }

    private function getMembre($login){
        $stmt = $this->connexion->prepare("SELECT login, nom, prenom, mail, telephone, adresse, datedenaissance, typepermis, dateinscription FROM public.membre WHERE login=:log");
        $stmt->bindParam(":log",$login,\PDO::PARAM_STR);
        $stmt->execute();
        $row  = $stmt -> fetch();
        return $row;
    }
}