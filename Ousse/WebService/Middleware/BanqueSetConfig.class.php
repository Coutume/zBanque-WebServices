<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/10/2016
 * Time: 23:03
 */

namespace Ousse\WebService\Middleware;


use Ousse\Manager\SiloManager;
use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BanqueSetConfig extends BanqueService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $banque = $this->getBanqueManager()->getByName($args["nom"]);
        $reponse = array();

        if($banque !== null)
        {
            $contenu = $request->getBody()->getContents();

            $jsonObject = SiloManager::jsonDecode($contenu);

            $this->getBanqueManager()->setConfig($banque, $jsonObject);

            $reponse["message"] = "Entité ajoutée";
            $reponse['code'] = 42;

            return Reponse::postSuccess($response, $reponse);
        }

        return Reponse::postError($response, new \Exception("La banque est introuvable.", -1));
    }

}