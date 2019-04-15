<?php


namespace model;


class Usager extends DataAccess
{

    private $mail;
    private $create_time;
    private $facebookid;
    private $googleid;
    private $password;
    private $idville;


    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @return string
     */
    public function getCreateTime(): string
    {
        return $this->create_time;
    }

    /**
     * @return string
     */
    public function getFacebookid(): string
    {
        return $this->facebookid;
    }

    /**
     * @return string
     */
    public function getGoogleid(): string
    {
        return $this->googleid;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getIdville(): string
    {
        return $this->idville;
    }




    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = UsagerTable::$TABLE_NAME;
        $this->id = UsagerTable::$ID;
        $this->all_column = UsagerTable::getAllColums();
        $this->mail = UsagerTable::$MAIL;
        $this->create_time = UsagerTable::$CREATE_TIME;
        $this->facebookid = UsagerTable::$FACEBOOK;
        $this->googleid = UsagerTable::$GOOGLE;
        $this->password = UsagerTable::$PASSWORD;
        $this->idville = UsagerTable::$ID_VILLE;
    }

}