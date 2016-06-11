<?php
/**
 * Created by PhpStorm.
 * User: amaury
 * Date: 15/02/16
 * Time: 16:33
 */

namespace Ousse\WebService\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthCheckService extends Service
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        // If the user reached this point he is authenticated, as the middleware catches the
        // requests unauthenticated or authenticated without a post access.
        // This may evolve if the permissions become more complex.

        $user = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER']: "");

        $response->getBody()->write(json_encode([
            'username' => $user,
            'permissions' => [
                'can_post' => true
            ]
        ]));

        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}
