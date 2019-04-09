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


    /**
     * Méthode qui va chercher un ou des problème(s) par un ID.
     *
     * @param $id id du problème à chercher
     * @param $model
     * @return mixed résultat de la requête à la base de données.
     */
    public function getById($id, $model){
        $request = $this->_pdo->prepare("SELECT * FROM  " . $model->getTableName() . " WHERE " . $model->getId() ." = :". $model->getId());
        $request->execute([$model->getId() => $id]);
        return $request->fetchObject();
    }

    /**
     * @param $id
     * @param $model
     * @return bool
     */
    public function deleteByID($id,$model){
        $request = $this->_pdo->prepare("DELETE FROM " . $model->getTableName() . " WHERE " . $model->getId() .
            " = :" . $model->getId());
        return $request->execute([$model->getId() => $id]);
    }

    /**
     * @param $id
     * @param $model
     * @param $dataArray
     * @return bool
     */
    public function updateByID($id, $dataArray , $model){
        unset($dataArray["path"]) ;
        $query = "UPDATE " . $model->getTableName() . " SET ";
        foreach ($dataArray as $keys => $value){
            $query .= array_key_last($dataArray) === $keys ? $keys . " = :" . $keys : $keys . " = :" . $keys . ", ";
        }
        $query .= " WHERE " . $model->getId() . " = :" . $model->getId() ;
        $request = $this->_pdo->prepare($query);
        return $request->execute(array_merge($dataArray , array($model->getId()  => $id)));
    }

    /**
     * @param $model
     * @return array
     */
    public function getAll($model){
        $request = $this->_pdo->prepare("SELECT * FROM " . $model->getTableName());
        $request->execute();
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $dataArray
     * @param $model
     * @return bool
     */
    public function postSomething($dataArray, $model){
        unset($dataArray["path"]);
        //unset($getAllColumn[$idColumn]);
        $allColumn = "`" . implode( "`,`", $model->getAllColumn()) . "`";

        $sql = "INSERT INTO " . $model->getTableName() . "(" . $allColumn . ")"
            . " VALUES ( :" . implode(", :", $model->getAllColumn()) . ")" ;

        $finalArray = array_merge([$model->getId() => NULL],$dataArray);
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