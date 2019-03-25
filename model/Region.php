<?php
namespace  model;
/**
 * Created by PhpStorm.
 * User: fletch
 * Date: 19-03-24
 * Time: 20:38
 */
class Region{

    /**
     * @var PDO
     */
    private $_pdo;


    /**
     * Region constructor.
     */
    public function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }


    // Retourne l'enregistrement à partir
    // de sa clé primaire auto-increment
    public function getById($id){
        $statement = $this->_pdo->prepare("SELECT * FROM region WHERE idregion=?");
        $statement->execute([$id]);
        return $statement->fetchObject();
    }

}