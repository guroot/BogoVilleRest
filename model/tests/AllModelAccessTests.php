<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-16
 * Time: 13:06
 */

namespace model\tests;


use PHPUnit\Framework\TestCase;
use \model\indexTable;
use \model\accessibleModel;
use \model\DataAccess;
use \model\Legitimator;

class AllModelAccessTests extends TestCase
{

    public function testEvenement(){

        global $pdo;

        $evenementArray = array(
            'path' => "a_path",
            indexTable\EvenementTable::COLUMNS['ID'] => null,
            indexTable\EvenementTable::COLUMNS['NOM'] => "test_nom",
            indexTable\EvenementTable::COLUMNS['DATE'] => "2042-01-01 00:00:00",
            indexTable\EvenementTable::COLUMNS['ADRESSE'] => "test_adresse_evenement",
            indexTable\EvenementTable::COLUMNS['ID_VILLE'] => 1
        );
        $this->modelTest($evenementArray, new accessibleModel\Evenement($pdo), $pdo);
    }

    public function modelTest(array $modelTestArray, DataAccess $access, $pdo){

    //Set up avant le test
        $pdo->exec("TRUNCATE TABLE " . $access->getTableName());

    //Test : insert
        $toInsert = $modelTestArray;
        unset($toInsert[$access->getIdColumnName()]);
        $this->assertTrue($access->insert($toInsert), "Insert n'a pas fonctionné");

    //Attribue la bonne valeur à la colonne correspondant au ID et fait une copie
    //permettant de simuler un objet
        $modelTestArray[$access->getIdColumnName()] = $pdo->lastInsertId();
        $controlArray = $modelTestArray;
        unset($controlArray['path']);

    //Test : getOneById
        $lastInserted = $access->getOneById($modelTestArray[$access->getIdColumnName()]);
        $this->assertEquals((object)$controlArray, $lastInserted);


    //Test : update
        $valuesToUpdate = array();
        $valuesTypesArray = $access->getColumnsType();
        $count = 0;
        $foreignKeysArray = $access->getForeignKeyColumns();
        foreach($access->getColumns() as $key=>$value){
            if(!(in_array($value, $foreignKeysArray))) {
                switch ($valuesTypesArray[$count]) {
                    case "varchar" :
                        $valuesToUpdate[$value] = "value_updated";
                        break;
                    case "tinyint" :
                        $valuesToUpdate[$value] = (1) ? 0 : 1;
                        break;
                    case "int" :
                        $valuesToUpdate[$value] = ($value == 666) ? 777 : 666;
                        break;
                    case "decimal" :
                        $valuesToUpdate[$value] = ($value == 66.6) ? 77.7 : 66.6;
                        break;
                    case "blob" :
                        //TODO coder ça
                        break;
                    case "datetime" :
                        $valuesToUpdate[$value] = "1111-11-11 11:11:11";
                        break;
                    default :
                        //TODO on fait qqch ici?
                        break;
                }
                $count++;
            } else {
                $valuesToUpdate[$value] = $lastInserted->{$value};
            }
        }
        unset($valuesToUpdate[$access->getIdColumnName()]);
        $access->updateWithId($lastInserted->{$access->getIdColumnName()}, $valuesToUpdate);
        $updatedObject = $access->getOneById($lastInserted->{$access->getIdColumnName()});
        $this->assertNotEquals($lastInserted, $updatedObject);
        $valuesToUpdate[$access->getIdColumnName()] = $updatedObject->{$access->getIdColumnName()};
        $this->assertEquals((object)$valuesToUpdate, $updatedObject);

        //Test : getAll
        $all = $access->getAll();
        $count = 0;
        foreach($all as $value){
            var_dump($value);
            $this->assertEquals($access->getOneById($value->{$access->getIdColumnName()}), $value);
            $count++;
        }
        $this->assertTrue(sizeof($all) === $count, "Retourne pas le bon nombre d'enregistrement");

        //Test : deleteWithId
        $this->assertTrue($access->deleteWithId($updatedObject->{$access->getIdColumnName()}));

    }

}