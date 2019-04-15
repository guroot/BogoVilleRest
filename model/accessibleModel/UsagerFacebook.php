<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 13:03
 */

namespace model\accessibleModel;


class UsagerFacebook extends DataAccess{

    /**
     * UsagerFacebook constructor.
     */
    public function __construct($pdo){
        parent::__construct($pdo);
        $this->_idColumnName = UsagerFacebookTable::COLUMNS['ID'];
        $this->_columns = UsagerFacebookTable::COLUMNS;
    }

}