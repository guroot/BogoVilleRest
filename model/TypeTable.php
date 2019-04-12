<?php


namespace model;


class TypeTable
{

    public static $TABLE_NAME = "type";
    public static $ID = "idType";
    public static $NOM = "nom";
    public static $DESCRIPTION = "description";


    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("type", $data)]);
        return $data;
    }

}