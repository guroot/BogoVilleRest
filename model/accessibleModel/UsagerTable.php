<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 11:28
 */

namespace model\accessibleModel;


class UsagerTable
{

    const TABLE_NAME = "usager";

    const COLUMNS = [
        "ID" => "idUsager",
        "EMAIL" => "email",
        "CREATED" => "create_time",
        "FACEBOOK_ID" => "id_facebook",
        "GOOGLE_ID" => "id_google",
        "PASSWORD" => "password",
        "ID_VILLE" => "id_ville"
    ];

}