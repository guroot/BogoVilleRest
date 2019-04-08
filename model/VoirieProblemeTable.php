<?php
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 * Date: 2019-03-25
 * Time: 12:44
 */
namespace model;


class VoirieProblemeTable
{

    public static $table_name = "voirie_problemes";
    public static $id = "idVoirie_Problemes";
    public static $type = "idtype";
    public static $idstatut = "idstatut";
    public static $idmedia = "idmedia";
    public static $commentaire = "commentaire";
    public static $id_ville = "idville";
    public static $longitude = "longitude";
    public static $latitude = "latitude";


    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("voirie_problemes", $data)]);
        return $data;
    }
}