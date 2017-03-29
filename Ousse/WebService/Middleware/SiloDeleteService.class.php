<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 29/03/2017
 * Time: 18:55
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SiloDeleteService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $reponse = array();
        $silo = $this->getSiloManager()->getBypos($args['x'], $args['z'], $args['banque']);

        if($silo !== null)
        {
            $this->getSiloManager()->delete($silo);
            $reponse["message"] = "Le silo a bien été supprimé";
            $reponse["code"] = 0;

            Reponse::getSuccess($response, $reponse);
        }
        else
        {
            return Reponse::getError($response, new \Exception("Impossible de trouver un silo pour la banque {$args['banque']} au position x : {$args['x']}, z : {$args['z']}"));
        }

        return $reponse;
    }

}