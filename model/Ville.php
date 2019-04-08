<?php


namespace model;


class Ville extends DataAccess
{

    private $table_name;
    private $id;
    private $all_column;
    private $region;
    private $nom;
    private $actif;

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
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
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