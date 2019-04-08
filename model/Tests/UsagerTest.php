<?php

namespace model;
use PHPUnit\Framework\TestCase;

class UsagerTest extends TestCase
{
    public static function setUpBeforeClass(): void{
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". UsagerTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". VilleTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . RegionTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");


    }


    public function testPostUsager(){
        global $pdo;

        $regionModel = new \model\Region($pdo);
        $regionModel->postSomething([$regionModel->getNom() => "Québec"], $regionModel);
        $regionId = $pdo->lastInsertId();

        $villeModel = new \model\Ville($pdo);
        $villeModel->postSomething([$villeModel->getNom() => "Lévis", $villeModel->getRegion() => $regionId, $villeModel->getActif() => 1],
            $villeModel);
        $villeId = $pdo->lastInsertId();
        $usagerModel = new \model\Usager($pdo);

        $firtsArray = [$usagerModel->getMail() => "bellepatate@ginette.biz", $usagerModel->getFacebookid() => ""
                        ,$usagerModel->getGoogleid() => "", $usagerModel->getIdville() => $villeId,
                        $usagerModel->getCreateTime() => "2019-01-12",$usagerModel->getPassword() => "admin"];


        $usagerModel->postSomething($firtsArray, $usagerModel);
        $lastId = $pdo->lastInsertId();

        var_dump($lastId);
        $secondArray = $usagerModel->getById($lastId, $usagerModel);

        $lastChance = array_merge([$usagerModel->getId() => $lastId], $firtsArray) ;
        var_dump($lastChance);
        var_dump($secondArray);
        $this->assertEquals((object)$lastChance, $secondArray );

        return $lastId;


    }


    /**
     * @depends testPostUsager
     * @param $insertedId
     */
    public function testUpdatebyUserId($insertedId){
        global $pdo;
        $model = new \model\Usager($pdo);

        // Va chercher le dernier enregistrement fait.
        $test = $model->getById($insertedId, $model);
        $key = $model->getMail();
        // Remplace le champs qui va etre changé.
        $test->$key = "example.com";
        // Update la valeur
        $model->updatebyId($insertedId ,[$model->getMail() => "example.com"], $model);

        var_dump($test);
        // Va rechercher "l'objet" qui vient d'être modifié
        $test2 = $model->getById($insertedId, $model);
        var_dump($test2);
        $this->assertEquals($test,$test2);

    }

    /**
     * @depends testPostUsager
     * @param $firtsInsert
     */
    public function testGetAll($firtsInsert){
        global $pdo;
        $model = new \model\Usager($pdo);

        $secondArray = [$model->getMail() => "ginette_gingras@monique.biz", $model->getFacebookid() => ""
            , $model->getGoogleid() => "", $model->getIdville() => 1, $model->getCreateTime() => "2017-08-26"
            ,$model->getPassword() => "password"];

        $problemOne = $model->getById($firtsInsert,$model);
        $model->postSomething($secondArray, $model);
        $secondInsert = $pdo->lastInsertId();
        $problemTwo = $model->getById($secondInsert , $model);

        $array = $model->getAll($model);

        $this->assertEquals($problemOne, $array[0]);
        $this->assertEquals($problemTwo, $array[1]);
    }


    /**
     * @depends testPostUsager
     * @param $lastId
     */
    public function testDeleteById($lastId){
        global $pdo;
        $model = new \model\Usager($pdo);
        $result = $model->deleteById($lastId, $model);
        $hopeFalse = $model->getById($lastId, $model);
        $this->assertFalse($hopeFalse);

    }


}
