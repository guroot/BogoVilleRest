<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Fleury
 * Date: 19-03-24
 * Time: 20:16
 */

// Permet de charger automatiquement les librairies du framework Slim
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
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Ceci est un service Rest et ne devrait pas être accédé directement');
});


$pdo =  new PDO('mysql:host=localhost;dbname=bogoville', 'root', 'root');
//var_dump($pdo);



//**********************************************************************************************************************
//Voirie_Problemes
//**********************************************************************************************************************

/**
 * POST
 * Creer un nouveau problème dans la base de données et associe le problème a l'usager.
 *
 * Concerne VOIRIE_PROBLEMES et USAGER_PROBLEMES.
 */
$app->post('/usager/{usagerId}/probleme', function ($request, $response, $args) use($pdo){
    $voirieProblemeModel = new \model\VoirieProbleme($pdo);
    $usagerProblemeModel = new \model\UsagerProbleme($pdo);
    $dataArray = $request->getParams();
    $data = $voirieProblemeModel->postSomething($dataArray, $voirieProblemeModel);
    //$patate = $usagerProblemeModel->associateProblemIdWithUserId($pdo->lastInsertId(), $args['usagerId']);
    if($data && true)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});

/**
 * GET
 * Aller chercher tous les problème sans filtrer.
 */
$app->get('/probleme', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->getAll($problemeModel);
    if($data)
        $response = $response->withJson($problemeModel->getAll($problemeModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

/**
 * PUT
 * Modifie le probleme par son ID
 */
$app->put('/probleme/{id}', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $dataArray = $request->getParams();
    $data = $problemeModel->updatebyId($args['id'],$dataArray, $problemeModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

/**
 * GET
 * Recherche le nombre d'usagers qui ont signalés le problème.
 */
$app->get('/count/probleme/{id}', function ($request, $response, $args) use($pdo){
    $usagerProblemModel = new \model\UsagerProbleme($pdo);
    $data = $usagerProblemModel->getUsersCountForThisProblem($args['id']);
    if ($data){
        $response = $response->withJson($usagerProblemModel->getUsersCountForThisProblem($args['id']));

    }else{
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Fichier introuvable');
    }
    return $response;
});

/**
 * GET
 * Aller chercher un problème par son ID
 */
$app->get('/probleme/{id}', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->getById($args['id'],$problemeModel);
    if($data)
        $response = $response->withJson($problemeModel->getById($args['id'],$problemeModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->get('/probleme/ville/{idville}/type/{idtype}', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->getProblemsByTypeIdAndTownId($args['idtype'],$args["idville"]);
    if($data)
        $response = $response->withJson($problemeModel->getProblemsByTypeIdAndTownId($args['idtype'],$args["idville"]));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});
$app->get('/probleme/ville/{idville}/statut/{idstatut}', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->getProblemsByStatusIdAndTownId($args["idstatut"],$args['idville']);
    if($data)
        $response = $response->withJson($problemeModel->getProblemsByStatusIdAndTownId($args["idstatut"],$args['idville']));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});


/**
 * DELETE
 * Supprime le problème par son ID
 *
 *
 */
$app->delete('/probleme/{id}', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->deleteById($args['id'],$problemeModel);
    if ($data)
        $response = $response->withJson($problemeModel->deleteById($args['id'], $problemeModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement supprimé');
    return $response;
});

/**
 * DELETE
 * Dissocie un problème associé à un usager.
 *
 * ----------- USAGER_PROBLEMES -------------
 */
$app->delete('/usager/{usagerId}/probleme/{id}', function ($request, $response, $args) use($pdo){
    $usagerProblemeModel = new \model\UsagerProbleme($pdo);
    $data = $usagerProblemeModel->deleteProblemIdFromThisUserId($args['id'], $args['usagerId']);
    if ($data){
        $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write('Éléments dissociés');
    }else{
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Action impossible');
    }
    return $response;
});



/**
 * GET
 * Aller chercher tous les problèmes par le ID de la ville.
 */
$app->get('/ville/{villeId}/probleme', function ($request, $response, $args) use($pdo){
    $problemeModel = new \model\VoirieProbleme($pdo);
    $data = $problemeModel->getProblemsByTownId($args['villeId']);
    if($data)
        $response = $response->withJson($problemeModel->getProblemsByTownId($args['villeId']));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});


/**
 * GET
 * Aller chercher tous les problèmes associés à un usager
 */
$app->get('/usager/{usagerId}/probleme', function ($request, $response, $args) use($pdo){
    $userProbModel = new \model\UsagerProbleme($pdo);
    $voirieModel = new \model\VoirieProbleme($pdo);
    $data = $userProbModel->getProblemsByUserId($args['usagerId'], $voirieModel);
    if($data)
        $response = $response->withJson($userProbModel->getProblemsByUserId($args['usagerId'], $voirieModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

//**********************************************************************************************************************
//Ville
//**********************************************************************************************************************

$app->get('/ville', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $data = $villeModel->getAll($villeModel);
    if($data)
        $response = $response->withJson($villeModel->getAll($villeModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->get('/ville/{id}', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $data = $villeModel->getById($args["id"],$villeModel);
    if($data)
        $response = $response->withJson($villeModel->getById($args["id"],$villeModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->post('/ville', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $dataArray = $request->getParams();
    $data = $villeModel->postSomething($dataArray, $villeModel);
    if($data)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});


$app->put('/ville/{id}', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $dataArray = $request->getParams();
    $data = $villeModel->updatebyId($args['id'],$dataArray, $villeModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

$app->delete('/ville/{id}', function ($request, $response, $args) use($pdo){
    $villeModel = new \model\Ville($pdo);
    $data = $villeModel->deleteByID($args['id'], $villeModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

//**********************************************************************************************************************
// Usager
//**********************************************************************************************************************

$app->get('/usager', function ($request, $response, $args) use($pdo){
    $usagerModel = new \model\Usager($pdo);
    $data = $usagerModel->getAll($usagerModel);
    if($data)
        $response = $response->withJson($usagerModel->getAll($usagerModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->get('/usager/{id}', function ($request, $response, $args) use($pdo){
    $usagerModel = new \model\Usager($pdo);
    $data = $usagerModel->getById($args["id"], $usagerModel);
    if($data)
        $response = $response->withJson($usagerModel->getById($args["id"], $usagerModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->post('/usager', function ($request, $response, $args) use($pdo){
    $usagerModel = new \model\Usager($pdo);
    $dataArray = $request->getParams();
    $data = $usagerModel->postSomething($dataArray, $usagerModel);
    if($data)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});


$app->put('/usager/{id}', function ($request, $response, $args) use($pdo){
    $usagerModel = new \model\Usager($pdo);
    $dataArray = $request->getParams();
    $data = $usagerModel->updatebyId($args['id'],$dataArray,$usagerModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

$app->delete('/usager/{id}', function ($request, $response, $args) use($pdo){
    $usagerModel = new \model\Usager($pdo);
    $data = $usagerModel->deleteByID($args['id'], $usagerModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

//**********************************************************************************************************************
// Region
//**********************************************************************************************************************

$app->get('/region', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $data = $regionModel->getAll($regionModel);
    if($data)
        $response = $response->withJson($regionModel->getAll($regionModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->get('/region/{id}', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $data = $regionModel->getById($args["id"],$regionModel);
    if($data)
        $response = $response->withJson($regionModel->getById($args["id"],$regionModel));
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Enregistrement introuvable');
    return $response;
});

$app->post('/region', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $dataArray = $request->getParams();
    $data = $regionModel->postSomething($dataArray,$regionModel);
    if($data)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});


$app->put('/region/{id}', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $dataArray = $request->getParams();
    $data = $regionModel->updatebyId($args['id'],$dataArray, $regionModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément modifié');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

$app->delete('/region/{id}', function ($request, $response, $args) use($pdo){
    $regionModel = new \model\Region($pdo);
    $data = $regionModel->deleteByID($args['id'],$regionModel);
    if($data)
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément supprimé');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Erreur');
    return $response;
});

$app->post('/type', function ($request, $response, $args) use($pdo){
    $model = new \model\Type($pdo);
    $dataArray = $request->getParams();
    $data = $model->postSomething($dataArray, $model);
    if($data)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});

$app->post('/statut', function ($request, $response, $args) use($pdo){
    $model = new \model\Statut($pdo);
    $dataArray = $request->getParams();
    $data = $model->postSomething($dataArray, $model);
    if($data)
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Élément inséré.');
    else
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write('Un problème est survenu.');
    return $response;
});


// Run application
$app->run();

