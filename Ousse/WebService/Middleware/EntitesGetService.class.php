<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/06/2016
 * Time: 00:09
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EntitesGetService extends SiloService
{
    static $entitesAutorisees = array("Banque", "BlocTuile", "Coffre", "Item", "ItemStack", "Silo");
    
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface      $response, $args)
    {
        $reponse = array();

        if(in_array($args['entite'], EntitesGetService::$entitesAutorisees))
        {
            $where = explode('/', $args['whereParams']);
            $equals = explode('/', $args['equalsParams']);

            $entites = $this->getSiloManager()->getEntities($args['entite'], $this->construireConditions($where, $equals));

            $reponse['entite'] = $entites;
            $reponse['message'] = "Entité(s) récupérée(s).";
            $reponse['code'] = 42; // Je sais, c'est pas très pro. :D
            return Reponse::getSuccess($response, $reponse);
        }
        else
        {
            throw new \Exception("Cette entité n'existe pas ou vous n'avez pas le droit d'y accéder");
        }
    }

    /**
     * Construit un tableau de conditions (sous la forme colonne => valeur de la colonne pour chaque entrée)
     * @param array $where
     * @param array $equals
     * @return array
     * @throws \Exception
     */
    public function construireConditions(array $where, array $equals)
    {
        $conditions = array();
        if(count($where) == count($equals))
        {
            for ($i = 0; $i < count($where); $i++)
            {
                $conditions[$where[$i]] = $equals[$i];
            }
        }
        else
        {
            throw new \Exception("Le nombre de paramètres ne correspond pas.");
        }

        return $conditions;
    }
}