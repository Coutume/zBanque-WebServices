<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/10/2016
 * Time: 21:39
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BanqueGetService extends BanqueService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $reponse = array();

        if(isset($args["nom"]))
        {
            $reponse["entite"] = $this->getBanqueManager()->getByName($args["nom"]);
        }
        else
        {
            $reponse["entite"] = $this->getBanqueManager()->getAll();
        }

        if($reponse["entite"] !== null)
        {
            $reponse['message'] = "Entités récupérées.";
            $reponse['code'] = 42;
            return Reponse::getSuccess($response, $reponse);
        }

        return Reponse::getError($response, new \Exception("Aucune entité n'a été trouvée.", -1));
    }

}