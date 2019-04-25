<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 09:23
 */

namespace model\tests;

use \PHPUnit\Framework\TestCase;
use \model\indexTable;
use \model\accessibleModel;
use \model\DataAccess;

class DataAccessTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        global $pdo;
        $pdo->exec("TRUNCATE TABLE " . indexTable\EvenementTable::TABLE_NAME);
    }
    /**
     * @return string
     */
    public function testInsert()
    {
        global $pdo;
        $toInsert = array(
            indexTable\EvenementTable::COLUMNS['NOM'] => "test_nom",
            indexTable\EvenementTable::COLUMNS['DATE'] => "2042-01-01 00:00:00",
            indexTable\EvenementTable::COLUMNS['ADRESSE'] => "test_adresse_evenement",
            indexTable\EvenementTable::COLUMNS['ID_VILLE'] => 1
        );
        $evenement_access = new accessibleModel\Evenement($pdo);
        $this->assertTrue($evenement_access->insert($toInsert), "Insert n'a pas fonctionné");
        return $pdo->lastInsertId();
    }
    /**
     * @depends testInsert
     * @param $last_id
     * @return Integer last inserted ID
     */
    public function testGetOneById($last_id)
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $last_inserted = $evenement_access->getOneById($last_id);
        $this->assertEquals($last_inserted,
            (object)[indexTable\EvenementTable::COLUMNS['ID'] => $last_id,
                indexTable\EvenementTable::COLUMNS['NOM'] => "test_nom",
                indexTable\EvenementTable::COLUMNS['DATE'] => "2042-01-01 00:00:00",
                indexTable\EvenementTable::COLUMNS['ADRESSE'] => "test_adresse_evenement",
                indexTable\EvenementTable::COLUMNS['ID_VILLE'] => 1]
        );
        return $last_inserted->{indexTable\EvenementTable::COLUMNS['ID']};
    }
    /**
     * @depends testInsert
     * @param $last_id
     */
    public function testUpdate($last_id)
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $updated_name = "updated_test_nom";
        $toUpdate = array(
            indexTable\EvenementTable::COLUMNS['NOM'] => $updated_name
        );
        $evenement_access->updateWithId($last_id, $toUpdate);
        $eventToTest = $evenement_access->getOneById($last_id);
        $this->assertEquals($eventToTest->{indexTable\EvenementTable::COLUMNS['NOM']}, $updated_name);
    }
    public function testGetTableName()
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $this->assertTrue(indexTable\EvenementTable::TABLE_NAME === $evenement_access->getTableName(), "Ne renvoit pas le bon nom de table");
    }
    public function testGetAll()
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $all = $evenement_access->getAll();
        $this->assertTrue(sizeof($all) === 1, "Retourne pas le bon nombre d'enregistrement");
    }
    public function testGetIdColumnName()
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $this->assertEquals(indexTable\EvenementTable::COLUMNS['ID'], $evenement_access->getIdColumnName());
    }
    public function test__construct()
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $this->assertInstanceOf(
            DataAccess::class,
            $evenement_access
        );
    }
    /**
     * @depends testGetOneById
     * param $last_id
     */
    public function testDeleteWithId($last_id)
    {
        global $pdo;
        $evenement_access = new accessibleModel\Evenement($pdo);
        $this->assertTrue($evenement_access->deleteWithId($last_id));
    }
}