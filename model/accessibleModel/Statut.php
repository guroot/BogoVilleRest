<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 08:44
 */

namespace model\accessibleModel;

use model\indexTable\StatutTable;
use \model\DataAccess;


class Statut extends DataAccess
{
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->_idColumnName = StatutTable::COLUMNS['ID'];
        $this->_columns = StatutTable::COLUMNS;
    }
}