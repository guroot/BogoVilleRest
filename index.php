<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

require 'vendor/autoload.php';

//   __/\\\\\\\\\\\_______________________/\\\____________________________________________________/\\\_______________________________
//   __\/////\\\///_______________________\/\\\___________________________________________________\/\\\______________________________
//   _______\/\\\__________________________\/\\\_______________________________________/\\\\\\\\\__\/\\\___________/\\\\\\\\\________
//   ________\/\\\______/\\/\\\\\\__________\/\\\______/\\\\\\\\___/\\\____/\\\________/\\\/////\\\_\/\\\__________/\\\/////\\\______
//   _________\/\\\_____\/\\\////\\\____/\\\\\\\\\____/\\\/////\\\_\///\\\/\\\/________\/\\\\\\\\\\__\/\\\\\\\\\\__\/\\\\\\\\\\______
//   __________\/\\\_____\/\\\__\//\\\__/\\\////\\\___/\\\\\\\\\\\____\///\\\/__________\/\\\//////___\/\\\/////\\\_\/\\\//////______
//   ___________\/\\\_____\/\\\___\/\\\_\/\\\__\/\\\__\//\\///////______/\\\/\\\_________\/\\\_________\/\\\___\/\\\_\/\\\___________
//   _________/\\\\\\\\\\\_\/\\\___\/\\\_\//\\\\\\\/\\__\//\\\\\\\\\\__/\\\/\///\\\__/\\\_\/\\\_________\/\\\___\/\\\_\/\\\__________
//   _________\///////////__\///____\///___\///////\//____\//////////__\///____\///__\///__\///__________\///____\///__\///__________

ini_set("display_errors",true);

function authenticate()
{
    header('WWW-Authenticate: Basic realm=" Authentication System"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Vous devez entrer un identifiant et un mot de passe valides pour accéder
    à cette ressource.\n";
    exit;
}

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'admin') {
    authenticate();
} else {

}

session_start();
session_regenerate_id();
if(!isset($_SESSION['LAST_ACTIVITY'])){
    $_SESSION['LAST_ACTIVITY'] = time();
}
if (time() - $_SESSION['LAST_ACTIVITY'] > 1800) { //1800 secondes = 30m
    session_unset();
    session_destroy();
    session_start(['LAST_ACTIVITY' => time()]);
} else if(time() != $_SESSION['LAST_ACTIVITY']) {
    $SESSION['LAST_ACTIVITY'] = time();
}

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false
    ],
];
$c = new \Slim\Container($configuration);

// instantiate the App object
$app = new \Slim\App($c);

// Add route callbacks
$app->get('/', function ($request, $response, $args){
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});

$pdo =  new PDO('mysql:host=127.0.0.1;port=3306;dbname=bogoville', 'root', '');

    $app->get("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
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
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
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
                ->write('I\'m affraid I can\'t do that. You may not get those shit. I\'m affraid I have to delete you from this planet.');
        }
    });

    $app->get("/{field}/{fieldValue}/{model}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $event = new \model\Evenement($pdo);
            $event->getAllWithEqualCondition($args['fieldValue'], $args['field']);
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

    $app->get("/usager/validate/{email}/val", function ($request, $response, $args) use ($pdo){
        $usagerModel = new \model\accessibleModel\Usager($pdo);
        $data = $usagerModel->getByEmail($args['email']);
        var_dump("je suis dans rest index.php");
        if($data) {
            return $response->withJson($usagerModel->getByEmail($args['email']));
        } else {
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Enregistrement introuvable');
        }
    });

    $app->post("/{model}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
            $myGenericModel = new $className($pdo);
            $data = $request->getParsedBody();
            if ($data)
                return $response->withJson($myGenericModel->insert($request->getParams(), $data));
            else
                return $response->withStatus(500)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write('Cannot insert the object. It\'s too tight...');
        } else {
            return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('I\'m affraid I can\'t do that. You may not post this shit. I\'m affraid I have to delete you from this planet.');
        }
    });

    $app->put("/{model}/{id}", function ($request, $response, $args) use ($pdo) {
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
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
        if(\model\Legitimator::legitimate($args['model'], __DIR__ . "\model\accessibleModel")) {
            $className = "\model\\accessibleModel\\" . ucfirst($args['model']);
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
                ->write('I\'m affraid I can\'t do that. The model you ask for isn\'t legitimate. I\'m affraid I have to delete you from this planet.');
        }
    });

// Run application
    $app->run();

