<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-03
 * Time: 14:56
 */

namespace model;
use \PHPUnit\Framework\TestCase;

class DataAccessTest extends TestCase{

    public static function setUpBeforeClass():void{
        parent::setUpBeforeClass();
        global $pdo;
       $pdo->exec("TRUNCATE TABLE " . EvenementTable::TABLE_NAME);
    }

    /**
     * @return string
     */
    public function testInsert(){
        global $pdo;
        $toInsert = array(
            EvenementTable::COLUMNS['NOM']=>"test_nom",
            EvenementTable::COLUMNS['DATE']=>"2042-01-01 00:00:00",
            EvenementTable::COLUMNS['ADRESSE']=>"test_adresse_evenement",
            EvenementTable::COLUMNS['ID_VILLE']=>1
        );
        $evenement_access = new Evenement($pdo);
        $this->assertTrue($evenement_access->insert($toInsert), "Insert n'a pas fonctionné");
        return $pdo->lastInsertId();
    }

    /**
     * @depends testInsert
     * @param $last_id
     * @return Last inserted ID
     */
    public function testGetOneById($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $last_inserted = $evenement_access->getOneById($last_id);
        $this->assertEquals($last_inserted,
            (object)[   EvenementTable::COLUMNS['ID']=>$last_id,
                        EvenementTable::COLUMNS['NOM']=>"test_nom",
                        EvenementTable::COLUMNS['DATE']=>"2042-01-01 00:00:00",
                        EvenementTable::COLUMNS['ADRESSE']=>"test_adresse_evenement",
                        EvenementTable::COLUMNS['ID_VILLE']=>1]
        );
        return $last_inserted->{EvenementTable::COLUMNS['ID']};
    }

    /**
     * @depends testInsert
     * @param $last_id
     */
    public function testUpdate($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $updated_name = "updated_test_nom";
        $toUpdate = array(
            EvenementTable::COLUMNS['NOM']=>$updated_name
        );
        $evenement_access->updateWithId($last_id, $toUpdate);
        $eventToTest = $evenement_access->getOneById($last_id);
        $this->assertEquals($eventToTest->{EvenementTable::COLUMNS['NOM']}, $updated_name);
    }

    public function testGetTableName(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertTrue(EvenementTable::TABLE_NAME === $evenement_access->getTableName(), "Ne renvoit pas le bon nom de table");
    }

    public function testGetAll(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $all = $evenement_access->getAll();
        $this->assertTrue(sizeof($all) === 1, "Retourne pas le bon nombre d'enregistrement");
    }

    public function testGetIdColumnName(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertEquals(EvenementTable::COLUMNS['ID'], $evenement_access->getIdColumnName());
    }

    public function test__construct(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertInstanceOf(
            DataAccess::class,
            $evenement_access
        );
    }

    /**
     * @depends testGetOneById
     * param $last_id
     */
    public function testDeleteWithId($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertTrue($evenement_access->deleteWithId($last_id));
    }
}
