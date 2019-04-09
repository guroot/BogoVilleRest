<?php
namespace model;

class RegionTable {

    const TABLE_NAME = "region";
    const ID = "idregion";
    const NOM = "nom";
    const URL = "/region";
    const URL_REGION_ID = "/region/{id}";

    const COLUMNS =
        ["ID"=>"idregion",
        "NOM"=>"nom"];

    public static function getAllColums(){
        return self::COLUMNS;
    }

}