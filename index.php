<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

//Commentaire pour pouvoir push

require 'vendor/autoload.php';



$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

// instantiate the App object
$app = new \Slim\App($c);

// Add route callbacks
$app->get('/', function ($request, $response, $args){
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});

$pdo =  new PDO('mysql:host=localhost;dbname=bogoville', 'root', '');

    $app->get("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $myGenericModel->getOneById($args['id']);
            if ($data)
                $response = $response->withJson($myGenericModel->getOneById($args['id']));
            else
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Enregistrement introuvable');
            return $response;
        } else {
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('I\'m affraid I can\'t do that');
        }
    });

    $app->get("/{model}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $myGenericModel->getAll();
            if ($data)
                $response = $response->withJson($myGenericModel->getAll());
            else
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Enregistrement introuvable');
            return $response;
        } else {
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('I\'m affraid I can\'t do that');
        }
    });

    $app->get("/{field}/{fieldValue}/{model}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $event = new \model\Evenement($pdo);
            $event->getAllWithEqualCondition($args['fieldValue'], $args['field']);
            die();
            $data = $myGenericModel->getAllWithEqualCondition($args['fieldValue'], $args['field']);
            if ($data)
                $response = $response->withJson(getAllWithEqualCondition($args['field'], $args['fieldValue']));
            else
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Enregistrement introuvable');
            return $response;
        } else {
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('I\'m affraid I can\'t do that');
        }
    });

    $app->post("/{model}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $request->getParsedBody();
            if ($data)
                $response = $response->withJson($myGenericModel->insert($request->getParams(), $data));
            else
                return $response->withStatus(500)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Cannot insert the shit');
        } else {
            return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('I\'m affraid I can\'t do that');
        }
    });

    $app->put("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $request->getParsedBody();
            if ($data)
                $response = $myGenericModel->updateWithId($args['id'], $request->getParams());
            else
                return $response->withStatus(406)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Cannot update the shit');
        } else {
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('I\'m affraid I can\'t do that');
        }
    });

    $app->delete("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'])) {
            $className = "\model\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $myGenericModel->getOneById($args['id']);
            if ($data)
                $response = $response->withJson($myGenericModel->deleteWithId($args['id']));
            else
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Enregistrement introuvable');
            return $response;
        } else {
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('I\'m affraid I can\'t do that');
        }
    });

// Run application
    $app->run();

