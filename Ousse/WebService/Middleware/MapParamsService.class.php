<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:44
 */

namespace Ousse\WebService\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MapParamsService extends MapService
{
    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface $response, $args)
    {
        $response->getBody()->write(json_encode($this->getMap()->getParams()));
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}