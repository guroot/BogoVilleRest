<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 08:42
 */

namespace model\indexTable;


class NotificationTable
{
    const TABLE_NAME = "statut";

    const COLUMNS = [
        "ID" => "idNotification",
        "DATENOTIFICATION" => "date_notification",
        "DATEECHEANCE" => "date_echeance",
        "MESSAGE" => "message",
        "IDVILLE" => "id_ville"];
}