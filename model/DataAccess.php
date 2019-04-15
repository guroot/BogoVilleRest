<?php
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 * Date: 2019-03-27
 * Time: 09:20
 */

namespace model;


class DataAccess
{


    /**
     * @var \PDO
     */
    protected $_pdo;
    protected $table_name;
    protected $id;
    protected $all_column;
    protected $nom;
    protected $description;

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAllColumn()
    {
        return $this->all_column;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Méthode qui va chercher un ou des problème(s) par un ID.
     *
     * @param $id id du problème à chercher
     * @return mixed résultat de la requête à la base de données.
     */
    public function getById($id){
        $request = $this->_pdo->prepare("SELECT * FROM  " . $this->getTableName() . " WHERE " . $this->getId() ." = :". $this->getId());
        $request->execute([$this->getId() => $id]);
        return $request->fetchObject();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteByID($id){
        $request = $this->_pdo->prepare("DELETE FROM " . $this->getTableName() . " WHERE " . $this->getId() .
            " = :" . $this->getId());
        return $request->execute([$this->getId() => $id]);
    }

    /**
     * @param $id
     * @param $dataArray
     * @return bool
     */
    public function updateByID($id, $dataArray)
    {
        unset($dataArray["path"]);
        $query = "UPDATE " . $this->getTableName() . " SET ";
        foreach ($dataArray as $keys => $value) {
            $query .= \array_key_last($dataArray) === $keys ? $keys . " = :" . $keys : $keys . " = :" . $keys . ", ";
        }
        $query .= " WHERE " . $this->getId() . " = :" . $this->getId();
        $request = $this->_pdo->prepare($query);
        return $request->execute(array_merge($dataArray, array($this->getId() => $id)));
    }

    /**
     * @return array
     */
    public function getAll(){
        $request = $this->_pdo->prepare("SELECT * FROM " . $this->getTableName());
        $request->execute();
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $dataArray
     * @return bool
     */
    public function postSomething($dataArray){
        unset($dataArray["path"]);
        $allColumn = "`" . implode( "`,`", $this->getAllColumn()) . "`";

        $sql = "INSERT INTO " . $this->getTableName() . "(" . $allColumn . ")"
            . " VALUES ( :" . implode(", :", $this->getAllColumn()) . ")" ;

        $finalArray = array_merge([$this->getId() => NULL],$dataArray);
        // Vérification si la valeur est une chaine vide ou null en format string
        foreach ($finalArray as $key => $value){
            if ($value === '' || strtolower($value) === 'null' ) {
                $finalArray[$key] = NULL;
            }
        }
        $request = $this->_pdo->prepare($sql);
        return $request->execute($finalArray);
    }

}