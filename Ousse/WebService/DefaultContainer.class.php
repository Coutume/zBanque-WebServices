<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 06/02/2016
 * Time: 11:07
 */

namespace Ousse\WebService;

use Psr\Http\Message\RequestInterface;
use Slim\Container;
// TODO Repenser la gestion des erreurs
class DefaultContainer extends Container
{
    public function __construct()
    {
        parent::__construct();
        $this['errorHandler'] = function ($container) {
            return function (RequestInterface $request, $response, $exception) use ($container)
            {
                switch($request->getMethod())
                {
                    case "POST":
                        return Reponse::postError($response, $exception);
                        break;
                    case "GET":
                        return Reponse::getError($response, $exception);
                        break;
                    default:
                        return Reponse::error($response, $exception);
                        break;
                }

            };
        };
    }
}