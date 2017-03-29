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

            // Suppression des silos renseignés dans le JSON
            if(isset($jsonObject->reset))
            {
                if(is_array($jsonObject->reset))
                {
                    $nbSilosSuppr = $this->getSiloManager()->deleteMany($jsonObject->reset);
                    $reponse['reset'] = $nbSilosSuppr . " silos supprimés.";
                }
                else
                {
                    throw new \Exception("La propriété reset doit être un tableau contenant lesinfos sur les silos à remettre à zéro.");
                }
            }

            // Ajout ou mise à jour des silos
            if(isset($jsonObject->silos))
            {
                $this->getSiloManager()->addMany($jsonObject->silos);
                $reponse['silos'] = "Silos ajoutées ou mis à jour";
            }

            $reponse['message'] = "Opération terminée.";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::postSuccess($response, $reponse);
        }
        catch(\Exception $ex)
        {
            return Reponse::postError($response, $ex);
        }
    }
}