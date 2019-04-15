<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-12
 * Time: 13:54
 */

namespace model;

use \PHPUnit\Framework\TestCase;


class FunctionsTest extends TestCase
{

    public function testLegitimator()
    {
        $this->assertTrue(Legitimator::legitimate("evenement"));
        $this->assertTrue(Legitimator::legitimate("region"));
        $this->assertTrue(Legitimator::legitimate("Evenement"));
        $this->assertFalse(Legitimator::legitimate("DROP TABLE"));
    }

}