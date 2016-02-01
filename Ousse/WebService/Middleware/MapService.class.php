<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 01/02/2016
 * Time: 23:40
 */

namespace Ousse\WebService\Middleware;


use Ousse\Map\Map;

class MapService extends Service
{
    /**
     * @var Map
     */
    private $map;

    /**
     * MapService constructor.
     * Construit un nouveau service permettant d'exposer les méthodes de
     * la classe Map
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        parent::__construct();
        $this->map = $map;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }
}