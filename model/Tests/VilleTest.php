<?php

namespace model;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 */
class VilleTest extends TestCase
{

    public static function setUpBeforeClass(): void{
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". UsagerTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". VilleTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . RegionTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ".VoirieProblemeTable::$table_name. ";" .
            "SET FOREIGN_KEY_CHECKS = 1;");
    }


    public function testPostVille(){
        global $pdo;
        $regionModel = new \model\Region($pdo);
        $regionPost = [$regionModel->getNom() => "Capitale Nationale"];
        $regionModel->postSomething($regionPost,$regionModel);
        $regionId = $pdo->lastInsertId();

        $villeModel = new \model\Ville($pdo);
        $villePost = [$villeModel->getNom() => "Québec", $villeModel->getActif() => 1, $villeModel->getRegion() => $regionId];
        $villeModel->postSomething($villePost, $villeModel);


        $villeId = $pdo->lastInsertId();
        $villeGet = $villeModel->getById($villeId,$villeModel);

        $this->assertEquals($villeGet, (object)array_merge([$villeModel->getId() => $villeId], $villePost));

        return $villeId ;


    }
    /**
     * @depends testPostVille
     * @param $insertedId
     */
    public function testUpdatebyUserId($insertedId){
        global $pdo;
        $model = new \model\Ville($pdo);

        // Va chercher le dernier enregistrement fait.
        $test = $model->getById($insertedId, $model);
        $key = $model->getNom();
        // Remplace le champs qui va etre changé.
        $test->$key = "Amqui";
        // Update la valeur
        $model->updatebyId($insertedId ,[$model->getNom() => "Amqui"], $model);
        // Va rechercher "l'objet" qui vient d'être modifié
        $test2 = $model->getById($insertedId, $model);
        $this->assertEquals($test,$test2);

    }

    /**
     * @depends testPostVille
     * @param $firtsInsert
     */
    public function testGetAll($firtsInsert){
        global $pdo;
        $model = new \model\Ville($pdo);

        $secondArray = [$model->getRegion() => 1, $model->getNom() => "Mirabel", $model->getActif() => 1];

        $problemOne = $model->getById($firtsInsert,$model);
        $model->postSomething($secondArray, $model);
        $secondInsert = $pdo->lastInsertId();
        $problemTwo = $model->getById($secondInsert , $model);

        $array = $model->getAll($model);

        $this->assertEquals($problemOne, $array[0]);
        $this->assertEquals($problemTwo, $array[1]);
    }


    /**
     * @depends testPostVille
     * @param $lastId
     */
    public function testDeleteById($lastId){
        global $pdo;
        $model = new \model\Ville($pdo);
        $result = $model->deleteById($lastId, $model);

        $hopeFalse = $model->getById($lastId, $model);

        $this->assertTrue($result);
        $this->assertFalse($hopeFalse);

    }

}
