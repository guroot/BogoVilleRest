<?php


namespace model;


class MediaTable
{

    public static $TABLE_NAME = "media";
    public static $ID = "id";
    public static $MEDIA = "media";
    public static $MIME = "mime";
    public static $FILENAME = "finename";


    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("media", $data)]);
        return $data;
    }

}