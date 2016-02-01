<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 28/01/2016
 * Time: 15:50
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ItemGetService extends SiloService
{

    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $item = $this->getSiloManager()->getItem($args['id'], $args['data']);

        if($item === null)
        {
            throw new \Exception("Impossible de trouver l'item demandé");
        }

        $reponse['entite'] = json_encode($item);
        $reponse['message'] = "Entité récupérée.";
        $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
        return Reponse::getSuccess($response, $reponse);
    }
}