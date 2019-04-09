<?php


namespace model;


class Statut extends DataAccess
{



    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = StatutTable::$TABLE_NAME;
        $this->id = StatutTable::$ID;
        $this->description = StatutTable::$DESCRIPTION;
        $this->all_column = StatutTable::getAllColums();

    }

}