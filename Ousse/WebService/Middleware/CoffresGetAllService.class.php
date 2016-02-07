<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 06/02/2016
 * Time: 11:30
 */

namespace Ousse\WebService\Middleware;



use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CoffresGetAllService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $reponse = array();
        $coffres = $this->getSiloManager()->getCoffres($args['id']);

        if($coffres !== null)
        {
            $reponse['entite'] = $coffres;
            $reponse['message'] = "Entités récupérées.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }

        throw new \Exception("Impossible de trouver de coffres dans le silo n°{$args['id']}");
    }
}