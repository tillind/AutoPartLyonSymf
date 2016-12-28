<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 14/12/2016
 * Time: 11:49
 */

namespace Base\AutoPartBundle\Business;


class Connection
{
    public $pdo=null;
    private $chaine="pgsql:host=127.0.0.1;port=5432;dbname=AutoPartLyon";
    private $user="appsGen";
    private $pwd="appsGen";

    public function __construct()
    {
        try{
            $this->pdo = new \PDO($this->chaine,$this->user,$this->pwd,array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e)
        {
            // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
        }


    }

    /**
     * @return null|\PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}