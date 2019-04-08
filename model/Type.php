<?php


namespace model;


class Type extends DataAccess
{

    private $table_name;
    private $all_column;
    private $nom;
    private $description;
    private $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
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
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }



    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = TypeTable::$TABLE_NAME;
        $this->nom = TypeTable::$NOM;
        $this->id = TypeTable::$ID;
        $this->description = TypeTable::$DESCRIPTION;
        $this->all_column = TypeTable::getAllColums();
    }
}