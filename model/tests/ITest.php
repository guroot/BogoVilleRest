<?php

namespace model\Tests;

use model\accessibleModel\Region;
use PHPUnit\Framework\TestCase;

class ITest extends  TestCase
{

    public function testRegion()
    {
        global $pdo;

        $pdo->exec("DELETE FROM region WHERE id=999");
        $pdo->exec("DELETE FROM `type` WHERE id=666");

        //1 - Inserer des donnes dans region manuellement avec PDO -- conserver le id (pdo->getlartInsertid()) dans une variable
        $sql = "INSERT INTO region (idRegion, nom) VALUES (999, 'shawi')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lastID = $pdo->lastInsertId();



        //2 -  Inserer des donnes dans type avec PDO
            $sql ="INSERT INTO type (idType, nom, description) VALUES (666,'bye','bye')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lastIDType = $pdo->lastInsertId();

        //3 - Appeller la methode deq Region - getOneBy avec comme paramere de ID le SQL suivant
            $model = new Region($pdo);
            $row = $model->getOneById($lastID ." UNION ALL SELECT idType,nom FROM type WHERE idType=666 order by idregion");

        // : 1 UNION ALL SELECT * FROM type;

        //4 - Valider le resultat
        if($row !== false)
            $this->assertTrue($row->idregion !== "666");
        else
            $this->assertTrue(true);


    }



}
