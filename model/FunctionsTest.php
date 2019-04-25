<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-12
 * Time: 13:54
 */

namespace model;

use \PHPUnit\Framework\TestCase;
use \model\accessibleModel;


class FunctionsTest extends TestCase
{

    public function testLegitimatorLegitimate(){
        $this->assertTrue(Legitimator::legitimate("evenement", __DIR__ . "\accessibleModel"));
        $this->assertTrue(Legitimator::legitimate("region", __DIR__ . "\accessibleModel"));
        $this->assertTrue(Legitimator::legitimate("Evenement", __DIR__ . "\accessibleModel"));
        $this->assertFalse(Legitimator::legitimate("DROP TABLE", __DIR__ . "\accessibleModel"));
    }

    public function testLegitimatorIsStringADate(){

    }

    //TODO tester ça ici??? ou avec les autres tests de DataAccess???
    public function testBlabla(){
        global $pdo;
        $eventAccess = new \model\accessibleModel\Evenement($pdo);
        var_dump($eventAccess->getColumnsType());
        die();
    }

}