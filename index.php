<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

// Permet de charger automatiquement les librairies du framework Slim
require 'vendor/autoload.php';

// instantiate the App object
$app = new \Slim\App();

// Add route callbacks
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});


$pdo =  new PDO('mysql:host=localhost;dbname=bogoville', 'root', '');

//POULE

//********************************************************************************
//Régions
//********************************************************************************

$app->get('/region/{id}', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $data = $regionModel->getById($args['id']);
    if($data)
        $response = $response->withJson($regionModel->getById($args['id']));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});




// Run application
$app->run();

