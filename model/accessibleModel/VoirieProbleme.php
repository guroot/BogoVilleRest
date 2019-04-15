<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 10:57
 */

namespace model\accessibleModel;


class VoirieProbleme extends DataAccess {

    /**
     * VoirieProbleme constructor.
     */
    public function __construct($pdo){
        parent::__construct($pdo);
        $this->_idColumnName = ProblemeTable::COLUMNS['ID'];
        $this->_columns = ProblemeTable::COLUMNS;
    }

}