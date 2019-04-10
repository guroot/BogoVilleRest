<?php
namespace  model;
/**
 * Created by PhpStorm.
 * User: fletch
 * Date: 19-03-24
 * Time: 20:38
 */
class Region extends DataAccess {


    /**
     * Region constructor.
     */
    public function __construct($pdo)
    {
        $this->_pdo = $pdo;
        $this->table_name = RegionTable::$TABLE_NAME;
        $this->id = RegionTable::$ID;
        $this->all_column = RegionTable::getAllColums();
        $this->nom = RegionTable::$NOM;
    }



}