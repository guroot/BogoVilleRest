<?php

namespace model\indexTable;

class EvenementTable
{

    const TABLE_NAME = "evenement";

    const COLUMNS =
        ["ID" => "idEvenement",
            "NOM" => "nom",
            "DATE" => "date",
            "ADRESSE" => "adresse",
            "ID_VILLE" => "id_ville"];

}