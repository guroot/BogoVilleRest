<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:05
 */

namespace model\accessibleModel;

use model\indexTable\VilleTable;
use \model\DataAccess;

//   :::     ::: ::::::::::: :::        :::        ::::::::::
//   :+:     :+:     :+:     :+:        :+:        :+:
//   +:+     +:+     +:+     +:+        +:+        +:+
//   +#+     +:+     +#+     +#+        +#+        +#++:++#
//    +#+   +#+      +#+     +#+        +#+        +#+
//     #+#+#+#       #+#     #+#        #+#        #+#
//       ###     ########### ########## ########## ##########

class Ville extends DataAccess
{
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->_idColumnName = VilleTable::COLUMNS['ID'];
        $this->_columns = VilleTable::COLUMNS;
    }
}