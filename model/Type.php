<?php


namespace model;


class Type extends DataAccess
{


    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = TypeTable::$TABLE_NAME;
        $this->nom = TypeTable::$NOM;
        $this->id = TypeTable::$ID;
        $this->description = TypeTable::$DESCRIPTION;
        $this->all_column = TypeTable::getAllColums();
    }
}