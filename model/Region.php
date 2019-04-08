<?php
namespace  model;
/**
 * Created by PhpStorm.
 * User: fletch
 * Date: 19-03-24
 * Time: 20:38
 */
class Region extends DataAccess {

    private $table_name;
    private $id;
    private $all_column;
    private $nom;

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAllColumn(): array
    {
        return $this->all_column;
    }


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