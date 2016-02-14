<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:49
 */

namespace Ousse\WebService\Middleware;


use Ousse\Manager\SiloManager;
use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SiloPostService extends SiloService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface      $response, $args)
    {
        $reponse = array();
        try
        {
            $contenu = $request->getBody()->getContents();

            $jsonObject = SiloManager::jsonDecode($contenu);
            $this->getSiloManager()->addSilos($jsonObject);

            $reponse['message'] = "Entité ajoutée.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::postSuccess($response, $reponse);
        }
        catch(\Exception $ex)
        {
            return Reponse::postError($response, $ex);
        }
    }
}