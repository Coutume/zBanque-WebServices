<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 06/02/2016
 * Time: 11:07
 */

namespace Ousse\WebService;

use Slim\Container;

class DefaultContainer extends Container
{
    public function __construct()
    {
        parent::__construct();
        $this['errorHandler'] = function ($container) {
            return function ($request, $response, $exception) use ($container)
            {
                return Reponse::error($response, $exception);
            };
        };
    }
}