<?php

namespace model;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 */
class RegionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . UsagerTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . VilleTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . RegionTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");


    }


    public function testPostRegion()
    {
        global $pdo;
        $regionModel = new \model\Region($pdo);
        $regionPost = [$regionModel->getNom() => "Capitale Nationale"];
        $regionModel->postSomething($regionPost);
        $regionId = $pdo->lastInsertId();
        $regionGet = $regionModel->getById($regionId);

        $this->assertEquals($regionGet, (object)array_merge([$regionModel->getId() => $regionId], $regionPost));

        return $regionId;


    }


    /**
     * @depends testPostRegion
     * @param $insertedId
     */
    public function testUpdatebyRegionId($insertedId)
    {
        global $pdo;
        $model = new \model\Region($pdo);

        // Va chercher le dernier enregistrement fait.
        $regionGetOne = $model->getById($insertedId);
        $key = $model->getNom();
        // Remplace le champs qui va etre changé.
        $regionGetOne->$key = "Mauricie";
        // Update la valeur
        $model->updatebyId($insertedId, [$model->getNom() => "Mauricie"]);

        // Va rechercher "l'objet" qui vient d'être modifié
        $regionGet2 = $model->getById($insertedId);
        $this->assertEquals($regionGetOne, $regionGet2);

    }

    /**
     * @depends testPostRegion
     * @param $firtsInsert
     */
    public function testGetAll($firtsInsert)
    {
        global $pdo;
        $model = new \model\Region($pdo);
        $regionPost = [$model->getNom() => "Bas St-Laurent"];
        $problemOne = $model->getById($firtsInsert);
        $model->postSomething($regionPost);
        $secondInsert = $pdo->lastInsertId();
        $problemTwo = $model->getById($secondInsert);

        $array = $model->getAll();
        $this->assertEquals($problemOne, $array[0]);
        $this->assertEquals($problemTwo, $array[1]);
    }


    /**
     * @depends testPostRegion
     * @param $lastId
     */
    public function testDeleteById($lastId)
    {
        global $pdo;
        $model = new \model\Region($pdo);
        $result = $model->deleteById($lastId);
        $hopeFalse = $model->getById($lastId);
        $this->assertTrue($result);
        $this->assertFalse($hopeFalse);

    }

}
