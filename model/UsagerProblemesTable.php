<?php
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 * Date: 2019-03-27
 * Time: 09:43
 */

namespace model;


class UsagerProblemesTable
{

    public static $TABLE_NAME = "Usager_Problemes";
    public static $ID_USAGER = "idUsager";
    public static $ID_PROBLEME = "id_Problemes";


    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("Usager_Problemes", $data)]);
        return $data;
    }
}