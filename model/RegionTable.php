<?php


namespace model;


class RegionTable
{

    public static $TABLE_NAME = "region";
    public static $ID = "idregion";
    public static $NOM = "nom";

    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("region", $data)]);
        return $data;
    }

}