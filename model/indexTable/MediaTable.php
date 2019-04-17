<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:07
 */

namespace model\indexTable;


class MediaTable
{
    const TABLE_NAME = "media";

    const COLUMNS = [
        "ID" => "idMedia",
        "MEDIA" => "media",
        "MIME" => "mime",
        "FILENAME" => "filename"];
}