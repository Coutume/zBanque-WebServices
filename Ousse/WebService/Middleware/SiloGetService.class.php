<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:53
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SiloGetService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface      $response, $args)
    {
        $reponse = array();
        $silo = $this->getSiloManager()->getSilo($args['id']);

        if($silo !== null)
        {
            $reponse['entite'] = $silo;
            $reponse['message'] = "Entité récupérée.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }

        throw new \Exception("Impossible de trouver le silo n°{$args['id']}");
    }
}