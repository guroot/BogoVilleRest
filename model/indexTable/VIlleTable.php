<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:06
 */

namespace model\indexTable;

class VilleTable
{
    const TABLE_NAME = "ville";

    const COLUMNS = [
        "ID" => "idVille",
        "REGION" => "id_region",
        "NOM" => "nom",
        "ACTIF" => "actif"];
}