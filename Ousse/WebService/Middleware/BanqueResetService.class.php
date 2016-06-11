<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 11/06/2016
 * Time: 21:16
 */

namespace Ousse\WebService\Middleware;

use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BanqueResetService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $items = $this->getSiloManager()->resetBanque($args['nom']);

        $reponse['entite'] = json_encode($items);
        $reponse['message'] = "Les silos liés à la banque {$args['nom']} ont été supprimés.";
        $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
        return Reponse::getSuccess($response, $reponse);
    }
}