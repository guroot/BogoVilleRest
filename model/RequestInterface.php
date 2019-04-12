<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-10
 * Time: 10:17
 */

namespace model;


interface RequestInterface
{
    public function getOneById($id);
    public function getAll();
    public function deleteWithId($id);
    public function insert(array $columns);
    public function updateWithId($id, array $data);
    public function getTableName();
    public function getIdColumnName();
    public function getCount();
    public function getAllWithEqualCondition($fieldName, $fieldValue);
}