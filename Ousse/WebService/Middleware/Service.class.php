<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:36
 */

namespace Ousse\WebService\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Service
{
    public function __construct()
    {
    }

    public function __invoke(ServerRequestInterface $request,
                             ResponseInterface      $response, $args)
    {
        throw new \Exception("Unimplemented function", -1);
    }
}