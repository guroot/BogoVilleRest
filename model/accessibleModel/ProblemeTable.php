<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 10:57
 */

namespace model\accessibleModel;


class ProblemeTable{

    const TABLE_NAME = "probleme";

    const COLUMNS =
        ["ID" => "idProbleme",
            "ID_TYPE" => "idType",
            "COMMENTAIRE"=>"commentaire",
            "ID_VILLE" => "id_ville",
            "ID_MEDIE" => "id_media",
            "LATITUDE" => "latitude",
            "LONGITUDE" => "longitude",
            "ID_STATUT" => "id_statut"];

}