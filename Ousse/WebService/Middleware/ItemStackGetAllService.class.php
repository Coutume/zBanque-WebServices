<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 07/02/2016
 * Time: 18:37
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ItemStackGetAllService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $reponse = array();
        $itemStacks = $this->getSiloManager()->getItemStacks($args['x'],$args['y'],$args['z']);

        if($itemStacks !== null)
        {
            $reponse['entite'] = $itemStacks;
            $reponse['message'] = "Entités récupérées.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }

        throw new \Exception("Impossible de trouver des itemStacks dans le coffre n°{$args['id']}");
    }
}