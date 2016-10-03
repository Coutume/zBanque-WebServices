<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 04/10/2016
 * Time: 00:43
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;

class BlocTuileManager
{
    /**
     * Permet de gérer la persistance et la mise à jour
     * des entités Doctrine
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function getAll()
    {
        $bts = $this->entityManager->getRepository("\\Ousse\\Entite\\Banque")
            ->findAll();

        return $bts;
    }

    public function getByPos($x, $z)
    {
        return $this->entityManager->getRepository("\\Ousse\\Entite\\BlocTuile")
            ->findBy(array("x" => $x, "z" => $z));
    }
}