<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 10:40
 */

namespace model\accessibleModel;

use \model\indexTable\TypeTable;
use \model\DataAccess;


class Type extends DataAccess
{

    /**
     * Type constructor.
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->_idColumnName = TypeTable::COLUMNS['ID'];
        $this->_columns = TypeTable::COLUMNS;
    }
}