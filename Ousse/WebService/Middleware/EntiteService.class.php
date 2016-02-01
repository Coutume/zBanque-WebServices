<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 28/01/2016
 * Time: 15:33
 */

namespace Ousse\WebService\Middleware;


use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class EntiteService extends Service
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * EntiteService constructor.
     * Construit un service utilisant les entités Doctrine
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }


}