<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 13/02/2016
 * Time: 14:36
 */

namespace Ousse\WebService\Middleware;


use Ousse\WebService\Reponse;
use Ousse\WebService\WebAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthService extends EntiteService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        // Contrôle de connexion pour toute requête POST
        if($request->getMethod() == "POST")
        {
            $auth = new WebAuth($this->getManager());

            // Vérification si le client a déjà envoyé les informations de connexion
            if(isset($_SERVER['PHP_AUTH_USER']))
            {
                $user = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER']: "");
                $mdp = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_PW']: "");
                $utilisateur = $auth->login($user, $mdp);

                // Utilisateur identifié et autorisé à exécuter des requêtes POST
                if($utilisateur !== false && $utilisateur->canPost())
                {
                    $response = $args($request, $response);
                }
    else
        {
            throw new \Exception("L'utilisateur ou le mot de passe renseigné ne correspondent pas.");
        }
    }
    else
        {
            $response = Reponse::auth($response);
        }

    }
    else
        {
            $response = $args($request, $response);
        }

    return $response;
    }
}