<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 04/01/2016
 * Time: 19:02
 */
use Ousse\WebService\Reponse;

/**
 * Définition de la variable provenant de bootstrap.php
 * @var $entityManager Doctrine\ORM\EntityManager
 */

require_once 'autoload.php'; // Chargement automatique des classes Ousse
require_once 'vendor/autoload.php'; // Chargement automatique des classes provenant des dépendances (Slim, Doctrine, ..)
require_once 'bootstrap.php'; // Inclusion de $entityManager pour manipuler les entités

$map = new \Ousse\Map\Map();

$parametres = function (Psr\Http\Message\ServerRequestInterface $request,
                        Psr\Http\Message\ResponseInterface $response, $args)
use ($map) {


    $response->getBody()->write(json_encode($map->getParams()));
    $response = $response->withHeader('Content-type', 'application/json');

    return $response;
};

$postSilos = function (Psr\Http\Message\ServerRequestInterface $request,
                       Psr\Http\Message\ResponseInterface $response, $args)
use ($map, $entityManager) {
    $body = $response->getBody();
    $reponse = array();
    try
    {
        $contenu = $request->getBody()->getContents();
        $manager = new \Ousse\Silo\SiloManager($entityManager);

        $jsonObject = \Ousse\Silo\SiloManager::jsonDecode($contenu);
        $manager->addSilo($jsonObject);

        $reponse['message'] = "Entité ajoutée.";
        $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
        return Reponse::postSuccess($response, $reponse);
    }
    catch(\Exception $ex)
    {
        $reponse['message'] = "Une erreur est survenue lors de l'ajout. Consultez la variable erreur pour plus d'infos.";
        $reponse['erreur'] = $ex->getMessage();
        $reponse['code'] = $ex->getCode();
        return Reponse::postError($response, $reponse);
    }
};

$getSilo = function (Psr\Http\Message\ServerRequestInterface $request,
                       Psr\Http\Message\ResponseInterface $response, $args)
use ($map, $entityManager) {
    $body = $response->getBody();
    $reponse = array();
    try
    {
        $contenu = $request->getBody()->getContents();
        $manager = new \Ousse\Silo\SiloManager($entityManager);

        $silo = $manager->getSilo($args['id']);

        if($silo !== null)
        {
            $reponse['entite'] = json_encode($silo);
            $reponse['message'] = "Entité récupérée.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }

        $reponse['entite'] = "";
        $reponse['message'] = "Entité non trouvée";
        $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
        return Reponse::getSuccess($response, $reponse);
    }
    catch(\Exception $ex)
    {
        $reponse['message'] = "Une erreur est survenue lors de la récupération. Consultez la variable erreur pour plus d'infos.";
        $reponse['erreur'] = $ex->getMessage();
        $reponse['code'] = $ex->getCode();
        return Reponse::getError($response, $reponse);
    }
};

// TODO mettre en place une architecture plus propre pour les webservices
$app = new Slim\App();
$app->get('/parametres', $parametres);

$app->post('/silos', $postSilos);
$app->get('/silos/{id}', $getSilo);

$app->run();