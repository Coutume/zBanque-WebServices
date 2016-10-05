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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$container = new \Ousse\WebService\DefaultContainer();
$app = new Slim\App($container);

$app->add(new \Ousse\WebService\Middleware\AuthService($entityManager));

$app->get("/{entite}/where/{whereParams:.*}/equals/{equalsParams:.*}",
    new \Ousse\WebService\Middleware\EntitesGetService($entityManager));

$app->get('/ping', new \Ousse\WebService\Middleware\PingService());
$app->post('/check_auth', new Ousse\WebService\Middleware\AuthCheckService());

$app->get('/parametres/{banque}', new \Ousse\WebService\Middleware\ParamsService($entityManager));

$app->post('/silos', new \Ousse\WebService\Middleware\SiloPostService($entityManager));
$app->get('/silos/{id}', new \Ousse\WebService\Middleware\SiloGetService($entityManager));
$app->get('/silos/{id}/coffres', new \Ousse\WebService\Middleware\CoffresGetAllService($entityManager));

$app->get('/items/{id}/{data}', new \Ousse\WebService\Middleware\ItemGetService($entityManager));

$app->get('/coffres/{x}/{y}/{z}', new \Ousse\WebService\Middleware\CoffreGetService($entityManager));
$app->get('/coffres/{x}/{y}/{z}/stacks', new \Ousse\WebService\Middleware\ItemStackGetAllService($entityManager));

$app->delete('/banque/{nom}', new \Ousse\WebService\Middleware\BanqueResetService($entityManager));

$app->run();