<?php
/**
 * Created by PhpStorm.
 * User: mohammedkoutti
 * Date: 2019-04-05
 * Time: 09:20
 */

namespace model;


use PHPUnit\Framework\TestCase;

class VilleTest extends TestCase
{


    public function testPostVille()
    {
        global $pdo;
        $ville = new \model\Ville($pdo);

        $ville->insert("trois-rivière",1,1);
        $id = $pdo->lastInsertId();
        $tabville=$ville->getById($id);
        $this->assertEquals($tabville,(object)["idville"=>$id,"nom"=>"trois-rivière","region"=>1,"actif"=>1]);
        return $id;
    }


    /**
     * @depends testPostVille
     * @param $id
     */
    public function testPutVille($id){
        global $pdo;
        $ville = new \model\Ville($pdo);

        $ville->upDateById($id,"trois",1,1);
        $tabville=$ville->getById($id);
        $this->assertEquals($tabville,(object)["idville"=>$id,"nom"=>"trois","region"=>1,"actif"=>1]);


    }
    /**
     * @depends testPostVille
     * @param $id
     */
    public function testDelete($id){
        global $pdo;
        $ville = new \model\Ville($pdo);

        $ville->deleteByid($id);

        $tabville=$ville->getById($id);
        $this->assertEquals($tabville,false);
    }
    /**
     * @depends testPostVille
     * @param $id
     */
    public function testAllVille($id){

        global $pdo;
        $ville = new \model\Ville($pdo);

        $pdo->exec("SET FOREIGN_KEY_CHECKS=0;TRUNCATE TABLE ville;SET FOREIGN_KEY_CHECKS=1;");

        $ville->insert("trois-rivière",1,1);
        $ville->insert("trois",1,1);

        $tabville=$ville->getVille();

        $this->assertEquals($tabville,[(object)["idville"=>1,"nom"=>"trois-rivière","region"=>1,"actif"=>1]
            ,(object)["idville"=>2,"nom"=>"trois","region"=>1,"actif"=>1]]);

    }



}
