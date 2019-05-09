<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-15
 * Time: 11:26
 */

namespace model\accessibleModel;

use \model\indexTable\UsagerTable;
use \model\DataAccess;

//   :::    :::  ::::::::      :::      ::::::::  :::::::::: :::::::::
//   :+:    :+: :+:    :+:   :+: :+:   :+:    :+: :+:        :+:    :+:
//   +:+    +:+ +:+         +:+   +:+  +:+        +:+        +:+    +:+
//   +#+    +:+ +#++:++#++ +#++:++#++: :#:        +#++:++#   +#++:++#:
//   +#+    +#+        +#+ +#+     +#+ +#+   +#+# +#+        +#+    +#+
//   #+#    #+# #+#    #+# #+#     #+# #+#    #+# #+#        #+#    #+#
//    ########   ########  ###     ###  ########  ########## ###    ###

class Usager extends DataAccess
{

    /**
     * Usager constructor.
     */
    public function __construct($pdo){
        parent::__construct($pdo);
        $this->_idColumnName = UsagerTable::COLUMNS['ID'];
        $this->_columns = UsagerTable::COLUMNS;
    }

    public function getByEmail($email){
        $statement = $this->_pdo->prepare("SELECT * FROM {$this->_tableName} WHERE " . UsagerTable::COLUMNS['EMAIL'] . " =?");
        $statement->execute([$email]);
        return $statement->fetchObject();
    }

}