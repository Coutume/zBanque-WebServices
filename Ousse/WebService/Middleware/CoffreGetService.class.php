<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 06/02/2016
 * Time: 10:56
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CoffreGetService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $reponse = array();
        $coffre = $this->getSiloManager()->getCoffre($args['x'], $args['y'], $args['z']);

        if($coffre !== null)
        {
            $reponse['entite'] = $coffre;
            $reponse['message'] = "Entité récupérée.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }

        throw new \Exception("Impossible de trouver le coffre".
                             "au position x: {$args['x']}, y: {$args['y']}, z: {$args['z']}");
    }

}