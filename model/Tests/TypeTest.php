<?php

namespace model;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 */
class TypeTest extends TestCase
{
    public static function setUpBeforeClass(): void{
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". UsagerTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". VilleTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . RegionTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ".VoirieProblemeTable::$table_name. ";" . "SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . StatutTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . TypeTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . UsagerProblemesTable::$TABLE_NAME. "; SET FOREIGN_KEY_CHECKS = 1;");
    }


    public function testPostType(){
        global $pdo;
        $typeModel = new \model\Type($pdo);
        $typePost = [$typeModel->getNom() => "Arbre tombé", $typeModel->getDescription() => "Tombé directement dans la rue"];
        $typeModel->postSomething($typePost);
        $lastId = $pdo->lastInsertId();
        $typeGet = $typeModel->getById($lastId);

        $this->assertEquals($typeGet, (object)array_merge([$typeModel->getId() => $lastId], $typePost ));
        return $lastId;
    }

    /**
     * @depends testPostType
     * @param $typeId
     */
    public function testUpdateType($typeId){
        global $pdo;
        $typeModel = new \model\Type($pdo);
        $typeGet = $typeModel->getById($typeId);
        $key = $typeModel->getDescription();
        $typeGet->$key = "Tombé sur la voie ferrée";
        $typeModel->updateByID($typeId, [$typeModel->getDescription() => "Tombé sur la voie ferrée"]);
        $typePut = $typeModel->getById($typeId);

        $this->assertEquals($typeGet, $typePut);
    }

    /**
     * @depends testPostType
     * @param $firstId
     */
    public function testGetAll($firstId){
        global $pdo;
        $model = new \model\Type($pdo);
        $typeOne = $model->getById($firstId);
        $typePost = [$model->getNom() => "Train", $model->getDescription() => "Déraillement"];
        $model->postSomething($typePost);
        $secondId = $pdo->lastInsertId();
        $typeTwo = $model->getById($secondId);

        $array = $model->getAll();

        $this->assertEquals($typeOne, $array[0]);
        $this->assertEquals($typeTwo, $array[1]);
    }

    /**
     * @depends testPostType
     * @param $lastId
     */
    public function testDeleteById($lastId){
        global $pdo;
        $model = new \model\Type($pdo);
        $result = $model->deleteById($lastId);
        $hopeFalse = $model->getById($lastId);
        $this->assertTrue($result);
        $this->assertFalse($hopeFalse);

    }

}