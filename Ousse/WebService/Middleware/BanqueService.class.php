<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/10/2016
 * Time: 21:12
 */

namespace Ousse\WebService\Middleware;


use Doctrine\ORM\EntityManager;
use Ousse\Manager\BanqueManager;

class BanqueService extends EntiteService
{
    private $banqueManager;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);

        $this->banqueManager = new BanqueManager($entityManager);
    }

    /**
     * @return BanqueManager
     */
    public function getBanqueManager()
    {
        return $this->banqueManager;
    }
}