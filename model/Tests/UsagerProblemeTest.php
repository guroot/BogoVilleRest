<?php

namespace model;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: kevinteasdaledube
 */
class UsagerProblemeTest extends TestCase
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

    private function createThingsForTest() : array {
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

        $model = new \model\VoirieProbleme($pdo);
        $firstProb = [
            $model->getType() => $typeId,
            $model->getCommentaire() => "Patate",
            $model->getIdmedia() => "",
            $model->getIdville() => $villeId,
            $model->getLatitude() => 111.11111,
            $model->getLongitude() => 222.22222,
            $model->getIdstatut() => $statutId
        ];
        $model->postSomething($firstProb, $model);
        $problemId2 = $pdo->lastInsertId();
        $problemGet2 = $model->getById($problemId2, $model );

        $secondArray = [
            $model->getType() => $typeId,
            $model->getCommentaire() => "Patate",
            $model->getIdmedia() => "",
            $model->getIdville() => $villeId,
            $model->getLatitude() => 111.11111,
            $model->getLongitude() => 222.22222,
            $model->getIdstatut() => $statutId
        ];
        $model->postSomething($secondArray, $model);
        $problemId = $pdo->lastInsertId();
        $problemGet = $model->getById($problemId, $model );

        $usagerModel = new \model\Usager($pdo);

        $firtsArray = [
            $usagerModel->getMail() => "bellepatate@ginette.biz",
            $usagerModel->getFacebookid() => "",
            $usagerModel->getGoogleid() => "",
            $usagerModel->getIdville() => $villeId,
            $usagerModel->getCreateTime() => "2019-01-12",
            $usagerModel->getPassword() => "admin"
        ];
        $usagerModel->postSomething($firtsArray, $usagerModel);
        $usagerId = $pdo->lastInsertId();
        $secondUser = [
            $usagerModel->getMail() => "alfred_babouche@falala.biz",
            $usagerModel->getFacebookid() => "",
            $usagerModel->getGoogleid() => "",
            $usagerModel->getIdville() => $villeId,
            $usagerModel->getCreateTime() => "2019-01-12",
            $usagerModel->getPassword() => "password"
        ];

        $usagerModel->postSomething($secondUser, $usagerModel);
        $usagerId2 = $pdo->lastInsertId();
        $userGet = $usagerModel->getById($usagerId, $usagerModel);
        $userGet2 = $usagerModel->getById($usagerId2, $usagerModel);

        return [$typeId, $villeId, $statutId, $problemId, $usagerId, $userGet,
                $problemGet, $userGet2, $problemGet2, $usagerId2, $problemId2 ];
    }

    public function testGetProblemIdWithUserId(){
        global $pdo;
        $allThings = $this->createThingsForTest();
        $model = new \model\UsagerProbleme($pdo);
        $result1 = $model->associateProblemIdWithUserId($allThings[3], $allThings[4]);
        $result2 = $model->associateProblemIdWithUserId($allThings[3], $allThings[9]);
        $result3 = $model->associateProblemIdWithUserId($allThings[10], $allThings[9]);
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);
        $userModel = new \model\Usager($pdo);
        $masterUserGet = $userModel->getById($allThings[4], $userModel);


        $problemModel = new \model\VoirieProbleme($pdo);
        $masterProblemGet = $problemModel->getById($allThings[3], $problemModel);

        $this->assertEquals($allThings[5], $masterUserGet);
        $this->assertEquals($allThings[6], $masterProblemGet);

        return $allThings;
    }

    /**
     * @depends testGetProblemIdWithUserId
     * @param $array
     *
     *  [$typeId, $villeId, $statutId, $problemId, $usagerId, $userGet,
    $problemGet, $userGet2, $problemGet2, $usagerId2, $problemId2 ];
     */
    public function testGetProblemsByUserId($array){
        global $pdo;
        $model = new \model\UsagerProbleme($pdo);
        $allProblem = $model->getProblemsByUserId($array[9], new \model\VoirieProbleme($pdo));

        $this->assertEquals($allProblem[0], $array[8]);
        $this->assertEquals($allProblem[1], $array[6]);

    }

    /**
     * @depends testGetProblemIdWithUserId
     * @param $array
     */
    public function testGetUsersCountForThisProblem($array){
        global $pdo;
        $model = new UsagerProbleme($pdo);
        $getCount = $model->getUsersCountForThisProblem($array[3]);

        $value = current((array)$getCount);

        $this->assertEquals(2 , $value);

    }

    /*public function testAssociateProblemIdWithUserId()
    {
        // TODO vérifier avec le Project Owner si OK car testé avec testGetProblemIdWithUserId().
    }*/

    /**
     * @depends testGetProblemIdWithUserId
     * @param $array
     */
    public function testGetUsersByProblemId($array){
        global $pdo;
        $model = new UsagerProbleme($pdo);

        $getUsers = $model->getUsersByProblemId($array[3], new Usager($pdo));


        $this->assertEquals($array[5], $getUsers[0]);
        $this->assertEquals($array[7], $getUsers[1]);

    }

    /**
     * [$typeId, $villeId, $statutId, $problemId, $usagerId, $userGet,
     * $problemGet, $userGet2, $problemGet2, $usagerId2, $problemId2 ];
     *
     * $result1 = $model->associateProblemIdWithUserId($allThings[3], $allThings[4]);
     * $result2 = $model->associateProblemIdWithUserId($allThings[3], $allThings[9]);
     * $result3 = $model->associateProblemIdWithUserId($allThings[10], $allThings[9]);
     *
     * @depends testGetProblemIdWithUserId
     * @param $array
     */
    public function testDeleteProblemIdFromThisUserId($array){
        global $pdo;
        $model = new UsagerProbleme($pdo);
        $bool = $model->deleteProblemIdFromThisUserId($array[3], $array[4]);

        $shouldBeFalse = $model->getProblemIdWithUserId($array[4], $array[3]);

        $this->assertFalse($shouldBeFalse);
        $this->assertTrue($bool);
    }
}
