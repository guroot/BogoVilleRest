<?php


namespace model;


class StatutTable
{

    public static $TABLE_NAME = "statut";
    public static $ID = "id";
    public static $DESCRIPTION = "description";


    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("statut", $data)]);
        return $data;
    }

}