<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 04/01/2016
 * Time: 19:02
 */

require_once __DIR__.'/autoload.php'; // Chargement automatique des classes Ousse
require_once 'vendor/autoload.php'; // Chargement automatique des classes provenant des dépendances (Slim, Doctrine, ..)
require_once __DIR__.'/bootstrap.php'; // Inclusion de $entityManager pour manipuler les entités

$map = new \Ousse\Map\Map();
$container = new \Ousse\WebService\DefaultContainer();
$app = new Slim\App($container);

$app->get('/parametres', new \Ousse\WebService\Middleware\MapParamsService($map));

$app->post('/silos', new \Ousse\WebService\Middleware\SiloPostService($entityManager));
$app->get('/silos/{id}', new \Ousse\WebService\Middleware\SiloGetService($entityManager));

$app->get('/items/{id}/{data}', new \Ousse\WebService\Middleware\ItemGetService($entityManager));

$app->get('/coffres/{x}/{y}/{z}', new \Ousse\WebService\Middleware\CoffreGetService($entityManager));
$app->get('/silos/{id}/coffres', new \Ousse\WebService\Middleware\CoffresGetAllService($entityManager));

$app->get('/coffres/{x}/{y}/{z}/stacks', new \Ousse\WebService\Middleware\ItemStackGetAllService($entityManager));

$app->run();