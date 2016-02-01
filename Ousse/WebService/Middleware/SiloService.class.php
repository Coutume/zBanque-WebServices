<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 28/01/2016
 * Time: 15:41
 */

namespace Ousse\WebService\Middleware;


use Doctrine\ORM\EntityManager;
use Ousse\Silo\SiloManager;

class SiloService extends EntiteService
{
    /**
     * @var SiloManager
     */
    private $siloManager;

    public function __construct(EntityManager $manager)
    {
        parent::__construct($manager);
        $this->siloManager = new SiloManager($this->getManager());
    }

    /**
     * @return SiloManager
     */
    public function getSiloManager()
    {
        return $this->siloManager;
    }
}