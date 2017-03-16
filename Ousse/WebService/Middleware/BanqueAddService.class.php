<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/10/2016
 * Time: 21:28
 */

namespace Ousse\WebService\Middleware;

use Ousse\Manager\SiloManager;
use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BanqueAddService extends BanqueService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $contenu = $request->getBody()->getContents();

        $jsonObject = SiloManager::jsonDecode($contenu);
        $this->getBanqueManager()->add($jsonObject);

        $reponse['message'] = "Entité ajoutée.";
        $reponse['code'] = 42;
        return Reponse::postSuccess($response, $reponse);
    }
}