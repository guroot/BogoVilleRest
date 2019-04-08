<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

// Permet de charger automatiquement les librairies du framework Slim
require 'vendor/autoload.php';

$isModelLegit = false;

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

// instantiate the App object
$app = new \Slim\App($c);

// Add route callbacks
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});

$pdo =  new PDO('mysql:host=localhost;dbname=bogoville', 'root', '');

/*$app->any("\{model}\*", function ($request, $response, $args) use($pdo, $isModelLegit){
    $test = false;
    $modelName = $args['model'];
    ucfirst($modelName);
    $index = 1;
    while(!$test){
        if(substr($modelName, $index, 1) == "_" && $index !== strlen($modelName)-1 ){
            substr_replace($modelName, strtoupper(substr($modelName, $index+1, 1)), $index+1, 1);
        }
        if($index == strlen($modelName)-1){
            $test = true;
            $modelName = str_replace("_", '', $modelName);
        }
        $index++;
    }
    if(is_subclass_of($args['model'], get_class(\model\DataAccess::class))){
        global $isModelLegit;
        $isModelLegit= true;
    };
});

if($isModelLegit) {*/

    $app->get("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        $className =  "\model\\".ucfirst($args['model']);
        $myGenericModel = new $className($pdo);
        $data = $myGenericModel->getOneShitById($args['id']);
        if ($data)
            $response = $response->withJson($myGenericModel->getOneShitById($args['id']));
        else
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Enregistrement introuvable');
        return $response;
    });

    $app->get("/{model}", function ($request, $response, $args) use ($pdo) {
        $className =  "\model\\".ucfirst($args['model']);
        $myGenericModel = new $className($pdo);
        $data = $myGenericModel->getAllTheShit();
        if ($data)
            $response = $response->withJson($myGenericModel->getAllTheShit());
        else
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Enregistrement introuvable');
        return $response;
    });

    $app->post("/{model}", function ($request, $response, $args) use ($pdo) {
        $className = "\model\\".ucfirst($args['model']);
        $myGenericModel = new $className($pdo);
        $data = $request->getParsedBody();
        if($data)
            $response = $response->withJson($myGenericModel->insertTheShit($request->getParams(), $data));
        else
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Cannot insert the shit');
    });

    $app->put("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        $className = "\model\\".ucfirst($args['model']);
        $myGenericModel = new $className($pdo);
        $data = $request->getParsedBody();
        var_dump($data);
        if($data)
            $response = $myGenericModel->updateTheShit($args['id'], $request->getParams());
        else
            return $response->withStatus(406)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Cannot update the shit');
    });

    $app->delete("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        $className = "\model\\".ucfirst($args['model']);
        $myGenericModel = new $className($pdo);
        $data = $myGenericModel->getOneShitById($args['id']);
        var_dump($data);
        if ($data)
            $response = $response->withJson($myGenericModel->deleteOneShitById($args['id']));
        else
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Enregistrement introuvable');
        return $response;
    });

/*} else {
    $app->get('/*', function ($request, $response, $args) {
        return $response->withStatus(201)->write('Roses are red, Violets are blue <br> This is not a place for someone like you');
    });}*/


// Run application
    $app->run();

