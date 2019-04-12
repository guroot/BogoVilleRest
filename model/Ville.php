<?php
/**
 * Created by PhpStorm.
 * User: mohammedkoutti
 * Date: 2019-03-25
 * Time: 13:42
 */

namespace model;


class Ville
{
    /**
     * @var \PDO
     */
    private $_pdo;


    /**
     * Ville constructor.
     */
    public function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }
    // Retourne toute les villes


    public function getVille(){
        $statement = $this->_pdo->prepare("SELECT * FROM ville");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);


    }
    // Retourne l'enregistrement à partir
    // de sa clé primaire auto-increment
    public function getById($id){
        $statement = $this->_pdo->prepare("SELECT * FROM ville WHERE idville=?");
        $statement->execute([$id]);
        return $statement->fetchObject();
    }

    public function insert($nom,$region,$actif){
        $prep =  $this->_pdo->prepare("INSERT INTO ville(nom,region,actif) VALUES(:nom,:region,:actif)");
        return $prep->execute([':nom' => $nom,':region' =>$region, ':actif' =>$actif]);

    }
    public function deleteByid($id){
        $prep=$this-> _pdo->prepare("DELETE FROM ville WHERE idville=?");
        $prep->execute([$id]);

    }
    public function upDateById($id,$nom,$region,$actif){
        $query = "UPDATE ville SET nom =:nom , region=:region, actif=:actif WHERE idville=?";
        $statement = $this->_pdo->prepare($query);
        $statement->execute([':nom' =>$nom,':region' =>$region,':actif'=>$actif,$id]);

    }


}