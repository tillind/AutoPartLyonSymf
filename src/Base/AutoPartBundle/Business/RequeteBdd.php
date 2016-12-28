<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 28/12/2016
 * Time: 11:06
 */

namespace Base\AutoPartBundle\Business;


class RequeteBdd
{
    private $connexion = null;

    public function __construct( $connect)
    {
           $this->connexion = $connect->getPdo();
    }

    public function userConnexion($login,$pwd){

    }

    public function getLesVoitures(){
        //if($this->connexion instanceof \PDO){
            $stmt = $this->connexion->query("SELECT idvoiture,etatvoiture,nomvoiture,idstation FROM voiture");
            while($lavoiture = $stmt->fetch()){
                var_dump($lavoiture);die;
            }
        //}else{
          //  echo"un probleme";die;
      //  }
    }


}