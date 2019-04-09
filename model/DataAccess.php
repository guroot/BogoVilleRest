<?php
namespace model;

interface RequestInterface {
    public function getOneShitById($id);
    public function getAllTheShit();
    public function deleteOneShitById($id);
    public function insertTheShit(array $columns/*, array $dataToInsert*/);
    public function updateTheShit($id, array $data);
    public function getTableName();
    public function getIdColumnName();
    public function getNumberOfShits();
}

class DataAccess implements RequestInterface{

    /**
     * @var \PDO
     */
    protected $_pdo;
    protected $_tableName;
    protected $_idColumnName;
    protected $_url;

    /**
     * Region constructor.
     */
    public function __construct($pdo){
        $this->_pdo = $pdo;
    }

    /**
     * Retourne un object sous forme de tableau
     *
     * @param $id ID de l'objet dans sa table SQL
     * @return mixed L'objet
     */
    public function getOneShitById($id){
        $statement = $this->_pdo->prepare("SELECT * FROM {$this->_tableName} WHERE {$this->_idColumnName} =?");
        $statement->execute([$id]);
        return $statement->fetchObject();
    }

    /**
     * Retourne toutes les données d'une table
     */
    public function getAllTheShit(){
        $statement = $this->_pdo->prepare("SELECT * FROM {$this->_tableName}");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Delete the row associated to an ID
     *
     * @param $id The corresponding ID to delete
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function deleteOneShitById($id){
        $statement = $this->_pdo->prepare("DELETE FROM {$this->_tableName} WHERE {$this->_idColumnName}=?");
        return $statement->execute([$id]);
    }

    /**
     * Sexy insert
     *
     * @param array $datas Associative array with column=>data
     * @return boolean True on success
     */
    public function insertTheShit(array $datas){
        unset($datas['path']);
        $sql = "INSERT INTO {$this->_tableName}("
            . implode(",", array_keys($datas))
            . ") VALUES (:"
            . implode(",:", array_keys($datas))
            .");";
        $statement = $this->_pdo->prepare($sql);
        return $statement->execute($datas);
    }

    /**
     * Update an object with a corresponding ID
     *
     * @param $id The ID
     * @param array $data An associative array of the datas to update. The keys need to
     *        have the same name as the column names
     */
    public function updateTheShit($id, array $data){
        unset($data['path']);
        $copy_data = $data;
        foreach($data as $key=>&$value){
            $value = $key . " = :" . $key;
        }
        $sql = "UPDATE {$this->_tableName} SET "
        . implode(", ", $data)
        . " WHERE {$this->_idColumnName}=:id";
        $statement = $this->_pdo->prepare($sql);
        $statement->execute(array_merge(["id"=>$id], $copy_data));
    }

    public function getNumberOfShits(){

    }

    /**
     * @return mixed
     */
    public function getTableName(){
        return $this->_tableName;
    }

    /**
     * @return mixed
     */
    public function getIdColumnName(){
        return $this->_idColumnName;
    }

}