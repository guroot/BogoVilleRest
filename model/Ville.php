<?php
/**
 * Created by PhpStorm.
 * User: mohammedkoutti
 * Date: 2019-03-25
 * Time: 13:42
 */


namespace model;


class Ville extends DataAccess
{


    private $region;
    private $actif;

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getActif(): string
    {
        return $this->actif;
    }





    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = VilleTable::$TABLE_NAME;
        $this->id = VilleTable::$ID;
        $this->all_column = VilleTable::getAllColums();
        $this->nom = VilleTable::$NOM;
        $this->region = VilleTable::$REGION;
        $this->actif = VilleTable::$ETAT;

    }



}