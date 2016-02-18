<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 22/01/2016
 * Time: 18:46
 */

namespace Ousse\WebService;


use Psr\Http\Message\ResponseInterface;

class Reponse
{
    public static function postSuccess(ResponseInterface $reponse, array $args)
    {
        $body = $reponse->getBody();

        $body->write(json_encode($args));

        return $reponse->withStatus(201)->withHeader('Content-type', 'application/json; charset=utf-8');
    }

    public static function postError(ResponseInterface $reponse, \Exception $ex)
    {
        $body = $reponse->getBody();
        $erreur = array();
        $erreur['message'] = "Une erreur est survenue lors de l'ajout. Consultez la variable erreur pour plus d'infos.";
        $erreur['erreur'] = $ex->getMessage();
        $erreur['code'] = $ex->getCode();
        $body->write(json_encode($erreur));

        return $reponse->withStatus(400)->withHeader('Content-type', 'application/json; charset=utf-8');
    }

    public static function getSuccess(ResponseInterface $reponse, array $args)
    {
        $body = $reponse->getBody();

        $body->write(json_encode($args));

        return $reponse->withStatus(200)->withHeader('Content-type', 'application/json; charset=utf-8');
    }

    public static function getError(ResponseInterface $reponse, \Exception $ex)
    {
        $body = $reponse->getBody();
        $erreur = array();
        $erreur['message'] = "Une erreur est survenue. Consultez la variable erreur pour plus d'infos.";
        $erreur['erreur'] = $ex->getMessage();
        $erreur['code'] = $ex->getCode();
        $body->write(json_encode($erreur));

        return $reponse->withStatus(404)->withHeader('Content-type', 'application/json; charset=utf-8');
    }

    public static function error(ResponseInterface $reponse, \Exception $ex)
    {
        $body = $reponse->getBody();
        $erreur = array();
        $erreur['message'] = "Une erreur est survenue. Consultez la variable erreur pour plus d'infos.";
        $erreur['erreur'] = $ex->getMessage();
        $erreur['code'] = $ex->getCode();
        $body->write(json_encode($erreur));

        return $reponse->withStatus(500)->withHeader('Content-type', 'application/json; charset=utf-8');
    }

    public static function auth(ResponseInterface $reponse, $message = null)
    {
        $erreur = array();
        $erreur['message'] = $message == null ? "Vous devez vous connecter pour pouvoir accéder à cette ressource." : $message;
        $reponse->getBody()->write(json_encode($erreur));
        return $reponse->withStatus(401)->withHeader('WWW-Authenticate', 'Basic realm="WallyWorld"');
    }
}
