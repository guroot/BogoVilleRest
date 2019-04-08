<?php
namespace model;

/**
 * Created by PhpStorm.
 * User: Meechy
 * Date: 3/25/2019
 * Time: 13:24
 */




class Type

{
    /**
     * @var \PDO
     */
    private $_pdo;




    //Constructeur de Type
    public function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }


    /**
     * GET
     * @param $id
     * @return mixed
     */

    public function getById($id){
        $statement = $this->_pdo->prepare("SELECT * FROM ". Type_Table::$TABLE_NAME ." WHERE ". Type_Table::$ID ." = ?");
        $statement->execute([$id]);
        return $statement->fetchObject();
    }


    /**
     * DELETE
     * @param $id
     * @return mixed
     */

    public function deleteById($id){
        $statement = $this->_pdo->prepare("DELETE FROM ". Type_Table::$TABLE_NAME ." WHERE ". Type_Table::$ID ."=?");
        return $statement->execute([$id]);
    }


    /**
     * INSERT
     * @param $nom
     * @param $description
     * @return mixed
     */

    public function insert($nom, $description){
        $allColums = "`" . implode("`,`", Type_Table::getAllColums()) . "`";


        $statement = $this->_pdo->prepare("INSERT INTO ". Type_Table::$TABLE_NAME . "(" . $allColums . ") VALUES(?, ?, ?);");
        $statement->execute([NULL, "$nom", "$description"]);
        return $statement->fetchObject();
    }


    /**
     * UPDATE
     * @param $id
     * @param $dataArray
     * @return mixed
     */

    public function update($id, $dataArray){

        unset($dataArray["path"]);
        $query = "UPDATE ". Type_Table::$TABLE_NAME . " SET ";
        foreach($dataArray as $keys => $value)
            if(array_key_last($dataArray) === $keys){
                $query .= $keys . " = :" . $keys;
            } else{
                $query .= $keys . " = :" . $keys . ", ";
            }
        $query .= " WHERE " . Type_Table::$ID . " = :" . Type_Table::$ID;
            $request = $this -> _pdo -> prepare($query);
            return $request->execute(array_merge($dataArray, array(Type_Table::$ID => $id)));
    }

}


