<?php

namespace model;
/**
 * Created by PhpStorm.
 * User: fletch
 * Date: 19-03-24
 * Time: 20:38
 */
class Region
{

    /**
     * @var \PDO
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
    public function getById($id)
    {
        $statement = $this->_pdo->prepare("SELECT * FROM ". Region_Table::$TABLE_NAME ." WHERE ". Region_Table::$ID ." = ?");
        $statement->execute([$id]);
        return $statement->fetchObject();
    }

    public function insert($nom)
    {
        $statement = $this->_pdo->prepare("INSERT INTO ". Region_Table::$TABLE_NAME ."(nom) ". " VALUES (?)");
        return $statement->execute([$nom]);


    }

    public function deleteById($id)
    {
        $statement = $this->_pdo->prepare("DELETE FROM ". Region_Table::$TABLE_NAME ." WHERE ". Region_Table::$ID ." = ?");
      return  $statement->execute([$id]);

    }

    public function update($id, $dataArray){

        unset($dataArray["path"]);
        $query = "UPDATE ". Region_Table::$TABLE_NAME . " SET ";
        foreach($dataArray as $keys => $value)
            if(array_key_last($dataArray) === $keys){
                $query .= $keys . " = :" . $keys;
            } else{
                $query .= $keys . " = :" . $keys . ", ";
            }
        $query .= " WHERE " . Region_Table::$ID . " = :" . Region_Table::$ID;

        $request = $this -> _pdo -> prepare($query);
        return $request->execute(array_merge($dataArray, array(Region_Table::$ID => $id)));
    }
}