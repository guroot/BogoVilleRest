<?php
namespace model\accessibleModel;
/**
 * Created by PhpStorm.
 * User: fletch
 * Date: 19-03-24
 * Time: 20:38
 */
class Region extends DataAccess {

    /**
     * Region constructor.
     */
    public function __construct($pdo){
        parent::__construct($pdo);
        $this->_idColumnName = RegionTable::COLUMNS['ID'];
        $this->_columns = RegionTable::COLUMNS;
    }

}