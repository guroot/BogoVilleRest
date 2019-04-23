<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:16
 */

namespace model\accessibleModel;

use \model\indexTable\ProblemeTable;
use \model\DataAccess;


class Probleme extends DataAccess
{
    /**
     * Probleme constructor.
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->_idColumnName = ProblemeTable::COLUMNS['ID'];
        $this->_columns = ProblemeTable::COLUMNS;
    }
}