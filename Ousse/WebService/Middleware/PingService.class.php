<?php
/**
 * Created by PhpStorm.
 * User: amaury
 * Date: 14/02/16
 * Time: 23:40
 */

namespace Ousse\WebService\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class PingService  extends Service
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $app_infos = json_decode(file_get_contents(__DIR__."/../../../composer.json"));
        $version = isset($app_infos->version) ? $app_infos->version : 'unknown';

        $response->getBody()->write(json_encode(array('version' => $version)));
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}
