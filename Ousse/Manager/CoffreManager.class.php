<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 24/01/2017
 * Time: 18:13
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;
use Ousse\Entite\Coffre;
use Ousse\Entite\Silo;
use stdClass;

class CoffreManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SiloManager
     */
    private $_siloManager;

    /**
     * @var ItemStackManager
     */
    private $_itemStackManager;

    public function __construct(SiloManager $em)
    {
        $this->entityManager = $em->getEntityManager();
        $this->_siloManager = $em;
        $this->_itemStackManager = new ItemStackManager($this->_siloManager);
    }

    /**
     * Ajoute le ou les coffres définis dans l'objet JSON
     * @param int $idSilo l'id du silo dans lequel ajouter les coffres
     * @param stdClass $jsonObject
     */
    public function addMany($idSilo, $jsonObject)
    {
        $silo = $this->_siloManager->get($idSilo);
        if($silo === null)
        {
            throw new \InvalidArgumentException("Le silo demandé n'existe pas.");
        }

        if(is_array($jsonObject))
        {
            $this->addManyTo($silo, $jsonObject);
        }
        else
        {
            $this->addTo($silo, $jsonObject);
        }

        $this->entityManager->flush();
    }

    /**
     * Ajoute les coffres contenus dans l'objet JSON dans le silo $silo
     * @param Silo $silo le silo dans lequel ajouter les coffres
     * @param array $jsonObject une liste des objets à ajouter
     * @throws \Exception Si un des éléments de la liste n'est pas un objet valide
     */
    public function addManyTo(Silo $silo, array $jsonObject)
    {
        foreach($jsonObject as $jsonCoffre)
        {
            if($jsonCoffre instanceof stdClass)
            {
                $this->addTo($silo, $jsonCoffre);
            }
            else
            {
                throw new \Exception("l'attribut coffres ne contient pas une liste valide.");
            }
        }
    }

    /**
     * Ajoute un coffre dans le silo $silo
     * @see addManyTo
     * @param Silo $silo
     * @param stdClass $jsonObject
     * @throws \Exception
     */
    protected function addTo(Silo $silo, stdClass $jsonObject)
    {
        $coffre = $this->get( $jsonObject->x, $jsonObject->y, $jsonObject->z,
            $jsonObject->map);

        if($coffre === null)
        {
            $coffre = new Coffre($jsonObject);
            $this->entityManager->persist($coffre);
        }

        $coffre->setSilo($silo);

        if(isset($jsonObject->itemStacks) && is_array($jsonObject->itemStacks))
        {
            $this->_itemStackManager->addManyTo($coffre, $jsonObject->itemStacks);
        }
    }

    /**
     * Récupère un coffre se trouvant aux coordonnées passées en paramètre
     * @param $x
     * @param $y
     * @param $z
     * @param $map
     * @return null|Coffre
     */
    public function get($x, $y, $z, $map)
    {
        $coffre = $this->entityManager  ->getRepository("\\Ousse\\Entite\\Coffre")
            ->findOneBy(array(  "x" => $x,
                "y" => $y,
                "z" => $z,
                "map" => $map));

        return $coffre;
    }

    public function getAllFor($idSilo)
    {
        $coffres = $this->entityManager  ->getRepository("\\Ousse\\Entite\\Coffre")
            ->findBy(array("silo" => $idSilo));

        return $coffres;
    }
}