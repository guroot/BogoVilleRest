<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-03
 * Time: 14:56
 */

namespace model;
use PHPUnit\Framework\TestCase;

class DataAccessTest extends TestCase{

    public static function setUpBeforeClass():void{
        parent::setUpBeforeClass();
        global $pdo;
       $pdo->exec("TRUNCATE TABLE " . EvenementTable::TABLE_NAME);
        /*$shitToInsert = array(
            EvenementTable::COLUMNS['NOM']=>"setup_test_nom",
            EvenementTable::COLUMNS['DATE']=>"2000-01-01 00:00:00",
            EvenementTable::COLUMNS['ADRESSE']=>"setup_test_adresse_evenement",
            EvenementTable::COLUMNS['ID_VILLE']=>1
        );
        $evenement_access = new Evenement($pdo);
        $evenement_access->insertTheShit($shitToInsert);*/
    }

    /**
     * @return string
     */
    public function testInsertTheShit(){
        global $pdo;
        $shitToInsert = array(
            EvenementTable::COLUMNS['NOM']=>"test_nom",
            EvenementTable::COLUMNS['DATE']=>"2042-01-01 00:00:00",
            EvenementTable::COLUMNS['ADRESSE']=>"test_adresse_evenement",
            EvenementTable::COLUMNS['ID_VILLE']=>1
        );
        $evenement_access = new Evenement($pdo);
        $this->assertTrue($evenement_access->insertTheShit($shitToInsert), "Ouache t'es poche t'as aucune valeur comme programmeur...");
        return $pdo->lastInsertId();
    }

    /**
     * @depends testInsertTheShit
     * @param $last_id
     * @return Last inserted ID
     */
    public function testGetOneShitById($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $last_inserted = $evenement_access->getOneShitById($last_id);
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
     * @depends testInsertTheShit
     * @param $last_id
     */
    public function testUpdateTheShit($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $updated_name = "updated_test_nom";
        $shitToUpdate = array(
            EvenementTable::COLUMNS['NOM']=>$updated_name
        );
        $evenement_access->updateTheShit($last_id, $shitToUpdate);
        $eventToTest = $evenement_access->getOneShitById($last_id);
        $this->assertEquals($eventToTest->{EvenementTable::COLUMNS['NOM']}, $updated_name);
    }

    public function testGetTableName(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertTrue(EvenementTable::TABLE_NAME === $evenement_access->getTableName(), "Ça renvoit pas la bonne shit...");
    }

    public function testGetAllTheShit(){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $all_the_shit = $evenement_access->getAllTheShit();
        $this->assertTrue(sizeof($all_the_shit) === 1, "Retourne pas le bon nombre d'enregistrement");
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
     * @depends testGetOneShitById
     * param $last_id
     */
    public function testDeleteOneShitById($last_id){
        global $pdo;
        $evenement_access = new Evenement($pdo);
        $this->assertTrue($evenement_access->deleteOneShitById($last_id));
    }
}
