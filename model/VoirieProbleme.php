<?php

/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 * Date: 2019-03-25
 * Time: 12:43
 */
namespace model;


class VoirieProbleme extends DataAccess
{

    private $type;
    private $idstatut;
    private $idmedia;
    private $commentaire;
    private $idville;
    private $longitude;
    private $latitude;


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIdstatut(): string
    {
        return $this->idstatut;
    }

    /**
     * @return string
     */
    public function getIdmedia(): string
    {
        return $this->idmedia;
    }

    /**
     * @return string
     */
    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    /**
     * @return string
     */
    public function getIdville(): string
    {
        return $this->idville;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }



    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = VoirieProblemeTable::$table_name;
        $this->id = VoirieProblemeTable::$id;
        $this->all_column = VoirieProblemeTable::getAllColums();
        $this->type = VoirieProblemeTable::$type;
        $this->idville = VoirieProblemeTable::$id_ville;
        $this->idstatut = VoirieProblemeTable::$idstatut;
        $this->idmedia = VoirieProblemeTable::$idmedia;
        $this->commentaire = VoirieProblemeTable::$commentaire;
        $this->latitude = VoirieProblemeTable::$latitude;
        $this->longitude = VoirieProblemeTable::$longitude;
    }



    public function getProblemsByTownId($idville){
        $request = $this->_pdo->prepare("SELECT * FROM " . $this->getTableName() . " WHERE ".
                                                $this->getIdville() . " = :" . $this->getIdville());
        $request->execute([$this->getIdville() => $idville]);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }


    public function getProblemsByTypeIdAndTownId($idType, $idville){
        $request = $this->_pdo->prepare("SELECT * FROM " . $this->getTableName() . " WHERE ".
                                        $this->getType() . " = :" . $this->getType() .
                                        " AND " . $this->getIdville() . " = :" . $this->getIdville());
        $request->execute([$this->getIdville() => $idville, $this->getType() => $idType]);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }

    // Vérifié avec POSTMAN reste a tester.
    public function getProblemsByStatusIdAndTownId($statusId, $idville){
        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->getIdstatut() . " = :" . $this->getIdstatut() .
            " AND " . $this->getIdville() . " = :" . $this->getIdville();
        $request = $this->_pdo->prepare($sql);
        $request->execute([$this->getIdville() => $idville, $this->getIdstatut() => $statusId]);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }


}