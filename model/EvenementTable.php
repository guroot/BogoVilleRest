<?php
namespace model;

class EvenementTable {

    const TABLE_NAME = "evenement";
    const URL = "/evenement";
    const URL_EVENEMENT_VILLE_ID = "/ville/{villdId}/evenement";
    const URL_EVENEMENT_ID = "/evenement/{id}";


    const COLUMNS =
            ["ID"=>"id_evenement",
            "NOM"=>"nom_evenement",
            "DATE"=>"date_evenement",
            "ADRESSE"=>"adresse_evenement",
            "ID_VILLE"=>"idville"];

    public static function getAllColumns(){
        return self::COLUMNS;
    }
}