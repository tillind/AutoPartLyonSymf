<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 14/12/2016
 * Time: 11:49
 */

namespace Employe\AutoPartBundle\Business;


class Connection
{
    public $pdo=null;
    private $chaine="pgsql:host=127.0.0.1;port=5432;dbname=AutoPartLyon";
    private $user="appsEmployer";
    private $pwd="appsEmployer";

    public function __construct()
    {
      $this->pdo = new \PDO($this->chaine,$this->user,$this->pwd);
    }
}