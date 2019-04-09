<?php

namespace model;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 */
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
        $this->assertTrue($result);
        $this->assertFalse($hopeFalse);

    }


    public function testGetProblemsByTownId(){
        global $pdo;
        $model = new VoirieProbleme($pdo);
        $regionId = $this->insertRegion();
        $townOneId = $this->insertTown($regionId, "Shawinigan");
        $townTwoId = $this->insertTown($regionId, "Trois-Rivières");
        $type = $this->insertType("Nide de poule", "Trou dans la chaussée");
        $statut = $this->insertStatus("Soumis");
        $problemOne = $this->insertProblem("Commentaire 1", $townOneId, $type,$statut);
        $problemTwo = $this->insertProblem("Commentaire 2", $townOneId, $type,$statut);
        $problemThree = $this->insertProblem("Commentaire 3", $townTwoId, $type,$statut);
        $problemFour = $this->insertProblem("Commentaire 4", $townOneId, $type,$statut);
        $problemArray = $model->getProblemsByTownId($townOneId);
        $problemArrayTwo = $model->getProblemsByTownId($townTwoId);
        $problemGETOne = $model->getById($problemOne, $model);
        $problemGETTwo = $model->getById($problemTwo, $model);
        $problemGETThree = $model->getById($problemThree, $model);
        $problemGETFour = $model->getById($problemFour, $model);

        $this->assertEquals($problemGETOne, $problemArray[0]);
        $this->assertEquals($problemGETTwo, $problemArray[1]);
        $this->assertEquals($problemGETFour, $problemArray[2]);
        $this->assertEquals($problemGETThree, $problemArrayTwo[0]);
    }

    public function testGetProblemsByTypeIdAndTownId(){
        global $pdo;
        $model = new VoirieProbleme($pdo);
        $regionId = $this->insertRegion();
        $townOneId = $this->insertTown($regionId, "Shawinigan");
        $townTwoId = $this->insertTown($regionId, "Trois-Rivières");
        $typeOne = $this->insertType("Train", "Déraillement de train");
        $typeTwo = $this->insertType("Nid de poule", "Trou dans la chaussée");
        $statut = $this->insertStatus("Relayé au MTQ");
        $problemOneId = $this->insertProblem("Allo 1",$townTwoId,$typeOne,$statut);
        $problemTwoId = $this->insertProblem("Allo 2",$townTwoId,$typeOne,$statut);
        $problemThreeId = $this->insertProblem("Allo 3",$townOneId,$typeTwo,$statut);
        $problemFourId = $this->insertProblem("Allo 4",$townTwoId,$typeOne,$statut);
        $problemFiveId = $this->insertProblem("Allo 5",$townOneId,$typeTwo,$statut);
        $problemSixId = $this->insertProblem("Allo 6",$townTwoId,$typeOne,$statut);
        $problemSevenId = $this->insertProblem("Allo 7",$townTwoId,$typeOne,$statut);
        $problemEightId = $this->insertProblem("Allo 8",$townOneId,$typeTwo,$statut);

        $problemArrayOne = $model->getProblemsByTypeIdAndTownId($typeOne,$townTwoId );
        $problemArrayTwo = $model->getProblemsByTypeIdAndTownId($typeTwo, $townOneId);

        $this->assertEquals($model->getById($problemOneId, $model),$problemArrayOne[0]);
        $this->assertEquals($model->getById($problemTwoId, $model),$problemArrayOne[1]);
        $this->assertEquals($model->getById($problemFourId, $model),$problemArrayOne[2]);
        $this->assertEquals($model->getById($problemSixId, $model),$problemArrayOne[3]);
        $this->assertEquals($model->getById($problemSevenId, $model),$problemArrayOne[4]);

        $this->assertEquals($model->getById($problemThreeId, $model),$problemArrayTwo[0]);
        $this->assertEquals($model->getById($problemFiveId, $model),$problemArrayTwo[1]);
        $this->assertEquals($model->getById($problemEightId, $model),$problemArrayTwo[2]);

    }


    public function testGetProblemsByStatusIdAndTownId(){
        global $pdo;
        $model = new VoirieProbleme($pdo);
        $regionId = $this->insertRegion();
        $townOneId = $this->insertTown($regionId, "Shawinigan");
        $townTwoId = $this->insertTown($regionId, "Trois-Rivières");
        $typeOne = $this->insertType("Train", "Déraillement de train");
        $statutOne = $this->insertStatus("Relayé au MTQ");
        $statutTwo = $this->insertStatus("Relayé a Hydro-Québec");
        $problemOneId = $this->insertProblem("Allo 1",$townOneId,$typeOne,$statutOne); //
        $problemTwoId = $this->insertProblem("Allo 2",$townTwoId,$typeOne,$statutTwo);
        $problemThreeId = $this->insertProblem("Allo 3",$townOneId,$typeOne,$statutOne);//
        $problemFourId = $this->insertProblem("Allo 4",$townTwoId,$typeOne,$statutTwo);
        $problemFiveId = $this->insertProblem("Allo 5",$townOneId,$typeOne,$statutOne);//
        $problemSixId = $this->insertProblem("Allo 6",$townTwoId,$typeOne,$statutTwo);
        $problemSevenId = $this->insertProblem("Allo 7",$townOneId,$typeOne,$statutOne);//
        $problemEightId = $this->insertProblem("Allo 8",$townTwoId,$typeOne,$statutTwo);

        $problemArrayOne = $model->getProblemsByStatusIdAndTownId($statutOne,$townOneId );
        $problemArrayTwo = $model->getProblemsByStatusIdAndTownId($statutTwo,$townTwoId );

        $this->assertEquals($model->getById($problemOneId, $model), $problemArrayOne[0]);
        $this->assertEquals($model->getById($problemThreeId, $model), $problemArrayOne[1]);
        $this->assertEquals($model->getById($problemFiveId, $model), $problemArrayOne[2]);
        $this->assertEquals($model->getById($problemSevenId, $model), $problemArrayOne[3]);

        $this->assertEquals($model->getById($problemTwoId, $model), $problemArrayTwo[0]);
        $this->assertEquals($model->getById($problemFourId, $model), $problemArrayTwo[1]);
        $this->assertEquals($model->getById($problemSixId, $model), $problemArrayTwo[2]);
        $this->assertEquals($model->getById($problemEightId, $model), $problemArrayTwo[3]);

    }

    private function insertProblem($comment, $town, $type, $statut){
        global $pdo;
        $problem = new VoirieProbleme($pdo);
        $problem->postSomething([
            $problem->getType()=> $type,
            $problem->getIdville() => $town,
            $problem->getIdstatut() => $statut,
            $problem->getIdmedia() => "",
            $problem->getCommentaire() => $comment,
            $problem->getLongitude() => 111.1111111,
            $problem->getLatitude() => 222.2222222], $problem);

        return $pdo->lastInsertId();
    }


    private function insertStatus($description){
        global $pdo;
        $status = new Statut($pdo);
        $statusResult = $status->postSomething([$status->getDescription() => $description], $status);
        if ($statusResult){
            return $pdo->lastInsertId();
        }
        return null;
    }

    private function insertRegion(){
        global $pdo;
        $region = new Region($pdo);
        $regionResult = $region->postSomething([$region->getNom() => "Mauricie"], $region);
        if ($regionResult){
            return $pdo->lastInsertId();
        }
        return null;
    }

    private function insertTown($regionId, $townName) {
        global $pdo;
        $ville = new Ville($pdo);
        if ($regionId > 0){
            $ville->postSomething([$ville->getRegion() => $regionId, $ville->getNom() => $townName,
                $ville->getActif() => 1], $ville);
            return $pdo->lastInsertId();
        }
        return null;
    }

    private function insertType($nom, $description){
        global $pdo;
        $type = new Type($pdo);
        $typeResult = $type->postSomething([$type->getNom() => $nom,
            $type->getDescription() => $description], $type);
        if ($typeResult){
            return $pdo->lastInsertId();
        }
        return null;
    }



}
