<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 08:40
 */

namespace model\accessibleModel;

use \model\indexTable\NotificationTable;
use \model\DataAccess;


class Notification extends DataAccess
{
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->_idColumnName = NotificationTable::COLUMNS['ID'];
        $this->_columns = NotificationTable::COLUMNS;
    }
}