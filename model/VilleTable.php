<?php


namespace model;


class VilleTable
{

    public static $TABLE_NAME = "ville";
    public static $ID = "idville";
    public static $REGION = "region";
    public static $NOM = "nom";
    public static $ETAT = "actif";

    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("ville", $data)]);
        return $data;
    }

}