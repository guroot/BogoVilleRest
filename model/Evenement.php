<?php
namespace  model;
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 19-03-25
 * Time: 13:12
 */
class Evenement extends DataAccess {

    /**
     * Evenement constructor.
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->_tableName = EvenementTable::TABLE_NAME;
        $this->_idColumnName = EvenementTable::COLUMNS['ID'];
        $this->_url = EvenementTable::URL_EVENEMENT_ID;
    }

    public function getAllColumns(){
        return EvenementTable::getAllColumns();
    }

}