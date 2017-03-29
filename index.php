<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 04/01/2016
 * Time: 19:02
 */

use Ousse\WebService\Middleware as Service;

require_once __DIR__.'/autoload.php'; // Chargement automatique des classes Ousse
require_once 'vendor/autoload.php'; // Chargement automatique des classes provenant des dépendances (Slim, Doctrine, ..)
require_once __DIR__.'/bootstrap.php'; // Inclusion de $entityManager pour manipuler les entités
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$container = new \Ousse\WebService\DefaultContainer();
$app = new Slim\App($container);
/*$bm = new \Ousse\Manager\BanqueManager($entityManager);
$map = new Ousse\Map\Map($entityManager);
$map->setCurrentBanque($bm->getByName('main'));
$map->genererTuilesToutesBanques();
echo 'généré';
die();*/

$app->add(new Service\AuthService($entityManager));

$app->get("/{entite}/where/{whereParams:.*}/equals/{equalsParams:.*}",
    new Service\EntitesGetService($entityManager));

$app->get('/ping',          new Service\PingService());
$app->post('/check_auth',   new Service\AuthCheckService());

$app->get('/parametres/{banque}', new Service\ParamsService($entityManager));

$app->post('/silos',             new Service\SiloPostService($entityManager));
$app->get('/silos/{id}',         new Service\SiloGetService($entityManager));
$app->get('/silos/{id}/coffres', new Service\CoffresGetAllService($entityManager));

$app->get('/items/{id}/{data}',     new Service\ItemGetService($entityManager));

$app->get('/coffres/{map}/{x}/{y}/{z}',        new Service\CoffreGetService($entityManager));
$app->get('/coffres/{map}/{x}/{y}/{z}/stacks', new Service\ItemStackGetAllService($entityManager));

$app->delete('/banque/reset/{nom}', new Service\BanqueResetService($entityManager));
$app->post('/banque',               new Service\BanqueAddService($entityManager));
$app->get('/banque',                new Service\BanqueGetService($entityManager)); // Récupération de toutes les banques
$app->get('/banque/{nom}',          new Service\BanqueGetService($entityManager)); // Récupération d'une banque par son nom
$app->post('/banque/{nom}/config',  new Service\BanqueSetConfig($entityManager));
$app->get('/banque/{banque}/config',new Service\ParamsService($entityManager)); // alias pour /parametres/{banque}
$app->delete('/banque/{banque}/silo/{x}/{z}', new Service\SiloDeleteService($entityManager));

$app->run();