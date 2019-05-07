<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:07
 */

namespace model\accessibleModel;

use \model\indexTable\MediaTable;
use \model\DataAccess;

//   ::::    ::::  :::::::::: ::::::::: :::::::::::     :::
//   +:+:+: :+:+:+ :+:        :+:    :+:    :+:       :+: :+:
//   +:+ +:+:+ +:+ +:+        +:+    +:+    +:+      +:+   +:+
//   +#+  +:+  +#+ +#++:++#   +#+    +:+    +#+     +#++:++#++:
//   +#+       +#+ +#+        +#+    +#+    +#+     +#+     +#+
//   #+#       #+# #+#        #+#    #+#    #+#     #+#     #+#
//   ###       ### ########## ######### ########### ###     ###

class Media extends DataAccess
{
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->_idColumnName = MediaTable::COLUMNS['ID'];
        $this->_nameColumnName = null;
        $this->_columns = MediaTable::COLUMNS;
    }
}