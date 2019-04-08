<?php


namespace model;


class Statut extends DataAccess
{

    private $table_name;
    private $id;
    private $description;
    private $all_column;

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getAllColumn(): array
    {
        return $this->all_column;
    }



    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = StatutTable::$TABLE_NAME;
        $this->id = StatutTable::$ID;
        $this->description = StatutTable::$DESCRIPTION;
        $this->all_column = StatutTable::getAllColums();

    }

}