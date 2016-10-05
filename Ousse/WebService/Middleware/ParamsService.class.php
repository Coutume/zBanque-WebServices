<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:44
 */

namespace Ousse\WebService\Middleware;


use Doctrine\ORM\EntityManager;
use Ousse\Manager\BanqueManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ParamsService extends Service
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $bm = new BanqueManager($this->entityManager);
        $banque = $bm->getByName($args['banque']);
        $response->getBody()->write(json_encode($banque->getConfig()));
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}