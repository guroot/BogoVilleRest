<?php


namespace model;


class UsagerTable
{
    public static $TABLE_NAME = "usager";
    public static $ID = "idusager";
    public static $MAIL = "email";
    public static $CREATE_TIME = "create_time";
    public static $FACEBOOK = "facebook_user_id";
    public static $GOOGLE = "usager_google_google_user_id";
    public static $PASSWORD = "password";
    public static $ID_VILLE = "idville";

    public static function getAllColums(){
        $class = new \ReflectionClass(new self());
        $data =  $class->getStaticProperties();
        unset($data[array_search("usager", $data)]);
        return $data;
    }

}