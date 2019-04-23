<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 13:08
 */

namespace model\accessibleModel;


class UsagerGoogle extends DataAccess
{

    /**
     * UsagerGoogle constructor.
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->_idColumnName = UsagerGoogleTable::COLUMNS['ID'];
        $this->_columns = UsagerGoogleTable::COLUMNS;
    }

}