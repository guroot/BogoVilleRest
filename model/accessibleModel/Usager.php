<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 11:26
 */

namespace model\accessibleModel;


class Usager extends DataAccess
{

    /**
     * Usager constructor.
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->_idColumnName = UsagerTable::COLUMNS['ID'];
        $this->_columns = UsagerTable::COLUMNS;
    }

}