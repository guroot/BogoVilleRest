<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

// Permet de charger automatiquement les librairies du framework Slim
require 'vendor/autoload.php';

$config = ['settings' => [
    'displayErrorDetails' => true
]];
// instantiate the App object
$app = new \Slim\App($config);

// Add route callbacks
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});


$pdo =  new PDO('mysql:host=localhost;dbname=bogoville', 'root', '');


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

//********************************************************************************
//Types
//********************************************************************************


/**
 * GET
 */

$app->get('/type/{id}', function ($request, $response, $args) use($pdo){
    $typeModel = new \model\Type($pdo);
    $data = $typeModel->getById($args['id']);

    if($data)
        $response = $response->withJson($typeModel->getById($args['id']));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

/**
 * DELETE
 */

$app->delete('/type/{id}', function ($request, $response, $args) use($pdo){
    $typeModel = new \model\Type($pdo);
    $data = $typeModel->deleteById($args['id']);
    if(!$data)
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/text;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

/**
 * INSERT
 */
$app->post('/type', function ($request, $response, $args) use($pdo){
    $typeModel = new \model\Type($pdo);
    $nom = $request->getParam("nom");
    $description = $request->getParam("description");
    $data = $typeModel->insert($nom, $description);
    if($data)
        $response = $response->withJson($typeModel->insert($nom, $description));
    return $response;
});

/**
 * UPDATE
 */
$app->put('/type/{id}', function ($request, $response, $args) use($pdo){
    $typeModel = new \model\Type($pdo);
    $dataArray = $request->getParams();
    $data = $typeModel->update($args['id'],$dataArray);
    if($data)
        $response = $response->withJson($typeModel->update($args['id'],$dataArray));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Impossible dupdater');
    return $response;
});


// Run application
$app->run();

