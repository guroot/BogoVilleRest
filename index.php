<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

// Permet de charger automatiquement les librairies du framework Slim
require 'vendor/autoload.php';

ini_set('display_errors',false);

$configuration = [
    'settings' => [
        'displayErrorDetails' =>false,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

// Add route callbacks
$app->get('/', function ($request, $response, $args) {

    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});



$pdo =  new PDO('mysql:host=127.0.0.1;port=3306;dbname=bogoville', 'root', '');

//********************************************************************************
//villes
//********************************************************************************
$app->get('/ville', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $data = $villeModel->getVille();
    if($data)
        $response = $response->withJson($villeModel->getVille());
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});


//********************************************************************************
//villes by id
//********************************************************************************

$app->get('/ville/{id}', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $data = $villeModel->getById($args['id']);
    if($data)
        $response = $response->withJson($villeModel->getById($args['id']));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

//**************************************************************
// Inserer les villes
//**************************************************************
$app->post('/ville', function ($request, $response, $args) use($pdo){
    $nom = $request->getParam("nom");
    $region = $request->getParam("region");
    $actif   = $request->getParam("actif");

    $villeModel = new \model\ville($pdo);
    $villeModel->insert( $nom,$region,$actif);
});
//**************************************************************
// supprimer les villes
//**************************************************************

$app->delete('/ville/{id}',function ($request,$response,$args) use ($pdo){
   $villeModel=new \model\Ville($pdo);
   $villeModel->deleteByid($args['id']);
});
//**************************************************************
// mise à jour les villes
//**************************************************************
$app->put('/ville/{id}',function($request,$response,$args) use ($pdo){

    $nom = $request->getParam("nom");
    $region = $request->getParam("region");
    $actif   = $request->getParam("actif");
    var_dump($nom);
    $villeModele=new \model\Ville($pdo);
    $villeModele->upDateById($args ['id'],$nom,$region,$actif);
});

// Run application
$app->run();

