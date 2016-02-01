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

$app = new Slim\App();
$app->get('/parametres', new \Ousse\WebService\Middleware\MapParamsService($map));

$app->post('/silos', new \Ousse\WebService\Middleware\SiloPostService($entityManager));
$app->get('/silos/{id}', new \Ousse\WebService\Middleware\SiloGetService($entityManager));

$app->get('/items/{id}/{data}', new \Ousse\WebService\Middleware\ItemGetService($entityManager));

$app->run();