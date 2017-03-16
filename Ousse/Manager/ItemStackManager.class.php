<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 24/01/2017
 * Time: 17:54
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;
use Ousse\Entite\Coffre;
use Ousse\Entite\ItemStack;
use stdClass;

class ItemStackManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SiloManager
     */
    private $_siloManager;

    public function __construct(SiloManager $em)
    {
        $this->entityManager = $em->getEntityManager();
        $this->_siloManager = $em;
    }

    public function add($x, $y, $z, $map, $jsonObject)
    {
        $coffre = $this->_siloManager->getCoffreManager()->get($x, $y, $z, $map);
        if($coffre === null)
        {
            throw new \InvalidArgumentException("Impossible de trouver un coffre à la position $x, $y, $z");
        }

        if(is_array($jsonObject))
        {
            $this->addManyTo($coffre, $jsonObject);
        }
        else
        {
            $this->addTo($coffre, $jsonObject);
        }

        $this->entityManager->flush();
    }

    public function getItemStacks($x, $y, $z, $map)
    {
        $itemStacks = null;
        $coffre = $this->_siloManager->getCoffreManager()->get($x, $y, $z, $map);
        if($coffre !== null)
        {
            $itemStacks = $this->entityManager->getRepository("\\Ousse\\Entite\\ItemStack")
                ->findBy(array("coffre" => $coffre->getId()));
        }

        return $itemStacks;
    }

    public function addManyTo(Coffre $coffre, array $jsonObject)
    {
        foreach($jsonObject as $jsonItemStack)
        {
            if($jsonItemStack instanceof stdClass)
            {
                $this->addTo($coffre, $jsonItemStack);
            }
            else
            {
                throw new \Exception("l'attribut itemStacks contient une liste avec des données invalides.");
            }
        }
    }

    public function addTo(Coffre $coffre, stdClass $jsonObject)
    {
        $item = null;
        if(isset($jsonObject->item))
        {
            $item = $this->_siloManager->getOraddItem($jsonObject->item);
        }
        else
        {
            throw new \Exception("La propriété item est manquante pour l'entité ItemStack");
        }

        $itemStack = new ItemStack($jsonObject);
        $itemStack->setCoffre($coffre);
        $itemStack->setItem($item);

        $this->entityManager->persist($itemStack);
    }
}