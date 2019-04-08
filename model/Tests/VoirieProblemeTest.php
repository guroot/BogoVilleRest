<?php

namespace model;
use PHPUnit\Framework\TestCase;

class VoirieProblemeTest extends TestCase
{


    public static function setUpBeforeClass(): void{
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". UsagerTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". VilleTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE " . RegionTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". TypeTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ". StatutTable::$TABLE_NAME . "; SET FOREIGN_KEY_CHECKS = 1;");
        $pdo->exec("TRUNCATE TABLE " . UsagerProblemesTable::$TABLE_NAME);
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE ".VoirieProblemeTable::$table_name. ";" .
            "SET FOREIGN_KEY_CHECKS = 1;");
    }


    private function insertThings(){
        global $pdo;
        $type = new \model\Type($pdo);
        $typePost = [$type->getNom() => "Patate", $type->getDescription() => "Écrasé sur la route"];
        $type->postSomething($typePost, $type);
        $typeId = $pdo->lastInsertId();

        $region = new \model\Region($pdo);
        $regionPost = [$region->getNom() => "Mauricie"];
        $region->postSomething($regionPost, $region);
        $regionId = $pdo->lastInsertId();

        $ville = new \model\Ville($pdo);
        $villePost = [$ville->getRegion() => $regionId, $ville->getNom() => "Shawinigan", $ville->getActif() => 1];
        $ville->postSomething($villePost, $ville);
        $villeId = $pdo->lastInsertId();

        $statut = new \model\Statut($pdo);
        $statutPost = [$statut->getDescription() => "En traitement"];
        $statut->postSomething($statutPost, $statut);
        $statutId = $pdo->lastInsertId();

        $media = new \model\Media($pdo);
        $mediaPost = [$media->getFilename() => "coquine.jpg", $media->getMime() => "ffjdnjknskgns",
                        $media->getMedia() => 0011011100011000100010];
        $media->postSomething($mediaPost, $media);
        $mediaId = $pdo->lastInsertId();

        return [$typeId, $villeId, $statutId, $mediaId];
    }

    /**
     *
     * @return string
     */
    public function testPostProblem(){
        global $pdo;
        $allStuff = $this->insertThings();
        $model = new \model\VoirieProbleme($pdo);
        $firtsArray = [
            $model->getType() => $allStuff[0],
            $model->getCommentaire() => "Fardoche",
            $model->getIdmedia() => "",
            $model->getIdville() => $allStuff[1],
            $model->getLatitude() => 123.456789,
            $model->getLongitude() => 987.654321,
            $model->getIdstatut() => $allStuff[2]
        ];
        $model->postSomething($firtsArray, $model);
        var_dump($firtsArray);
        $lastId = $pdo->lastInsertId();
        $secondArray = $model->getById($lastId,$model);
        $lastChance = array_merge([$model->getId() => $lastId], $firtsArray) ;

        $this->assertEquals((object)$lastChance, $secondArray );
        return $lastId;


    }


    /**
     * @depends testPostProblem
     * @param $insertedId
     */
    public function testUpdatebyId($insertedId){
        global $pdo;
        $model = new \model\VoirieProbleme($pdo);

        // Va chercher le dernier enregistrement fait.
        $test = $model->getById($insertedId, $model);
        $key = $model->getCommentaire();
        // Remplace le champs qui va etre changé.
        $test->$key = "testUpdateProblem";
        // Update la valeur
        $model->updatebyId($insertedId ,[$model->getCommentaire() => "testUpdateProblem"], $model);

        // Va rechercher "l'objet" qui vient d'être modifié
        $test2 = $model->getById($insertedId,$model);
        $this->assertEquals($test,$test2);

    }

    /**
     *
     * @depends testPostProblem
     * @param $firtsInsert
     */
    public function testGetAllProblem($firtsInsert){
        global $pdo;
        $model = new \model\VoirieProbleme($pdo);
        $second = $this->insertThings();
        var_dump($second);
        $secondArray = [
            $model->getType() => $second[0],
            $model->getCommentaire() => "Patate",
            $model->getIdmedia() => "",
            $model->getIdville() => $second[1],
            $model->getLatitude() => 111.11111,
            $model->getLongitude() => 222.22222,
            $model->getIdstatut() => $second[2]
        ];

        $problemOne = $model->getById($firtsInsert, $model);
        $model->postSomething($secondArray, $model);


        $problemTwo = $model->getById($pdo->lastInsertId(), $model);

        $array = $model->getAll($model);

        var_dump($problemTwo);
        var_dump($array);
        $this->assertEquals($problemOne, $array[0]);
        $this->assertEquals($problemTwo, $array[1]);
    }


    /**
     * @depends testPostProblem
     * @param $lastId
     */
    public function testDeleteById($lastId){
        global $pdo;
        $model = new \model\VoirieProbleme($pdo);
        $result = $model->deleteById($lastId, $model);
        $hopeFalse = $model->getById($lastId, $model);
        $this->assertFalse($hopeFalse);

    }



}
