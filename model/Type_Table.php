<?php
/**
 * Created by PhpStorm.
 * User: Meechy
 * Date: 3/25/2019
 * Time: 13:41
 */

namespace model;


class Type_Table
{
    public static $TABLE_NAME = "type";
    public static $ID = "idType";
    public static $NomType = "nom";
    public static $Description = "description";



    public function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data = $class->getStaticProperties();
        unset($data[array_search("type", $data)]);
        return $data;
    }


}