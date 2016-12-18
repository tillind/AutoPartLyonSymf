<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 14/12/2016
 * Time: 11:48
 */

use \Base\AutoPartBundle\Business\Connection;
class VoitureBdd{

    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function insertVoiture(\Base\AutoPartBundle\Business\Voiture $voiture){

    }

}