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
use Ousse\WebService\Reponse;
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
        $reponse = array();

        if($banque != null && $banque->getConfig() !== null)
        {
            $reponse["entite"] = $banque->getConfig();
            $reponse['message'] = "Entités récupérées.";
            $reponse['code'] = 42;
            return Reponse::getSuccess($response, $reponse);
        }

        return Reponse::getError($response, new \Exception("Aucune configuration n'a été trouvée pour la banque {$args["banque"]}.", -1));
    }
}