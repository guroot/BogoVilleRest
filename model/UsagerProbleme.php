<?php


namespace model;


class UsagerProbleme extends DataAccess
{
public function __construct($pdo){
    parent:: __construct($pdo);
    $this -> _columns = UsagerProblemesTable::COLUMS;


}

}