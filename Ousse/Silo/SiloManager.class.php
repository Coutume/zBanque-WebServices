<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 22/01/2016
 * Time: 11:46
 */

namespace Ousse\Silo;
use Doctrine\ORM\EntityManager;
use Ousse\Entite\Banque;
use Ousse\Entite\BlocTuile;
use Ousse\Entite\Coffre;
use Ousse\Entite\Item;
use Ousse\Entite\ItemStack;
use Ousse\Entite\Silo;
use stdClass;

/**
 * Gère les silos et leur contenu
 * TODO scinder la gestion des différents objets en plusieurs classes héritant de la même interface
 * Class SiloManager
 * @package Ousse\Silo
 */
class SiloManager
{
    /**
     * Permet de gérer la persistance et la mise à jour
     * des entités Doctrine
     * @var EntityManager
     */
    private $entityManager;

    public function getBanque($nom)
    {
        $banque = $this->entityManager->getRepository("\\Ousse\\Entite\\Banque")
            ->findOneBy(array("nom" => $nom));

        return $banque;
    }

    protected function getOraddBanque($jsonObject)
    {
        $nom = (isset($jsonObject->nom)) ? $jsonObject->nom: null;

        $banque = $this->getBanque($nom);
        if($banque === null)
        {
            $banque = new Banque($jsonObject);
            $this->entityManager->persist($banque);
        }

        return $banque;
    }

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function addSilo(StdClass $jsonObject)
    {
        if(isset($jsonObject->banque))
        {
            $silo = new Silo($jsonObject);
            $banque = $this->getOraddBanque($jsonObject->banque);

            $silo->setBanque($banque);
            $this->entityManager->persist($silo);

            if(isset($jsonObject->coffres) && is_array($jsonObject->coffres))
            {
                $this->addCoffresTo($silo, $jsonObject->coffres);
            }

            if(isset($jsonObject->itemPrincipal))
            {
                $item = $this->getOraddItem($jsonObject->itemPrincipal);
                $silo->setItemPrincipal($item);
            }

            $this->entityManager->flush();
        }
        else
        {
            throw new \Exception("Impossible de trouver les informations sur la banque dans le silo", -1);
        }

    }

    /**
     * @param $id
     * @return null|Silo
     */
    public function getSilo($id)
    {
        $silo = $this->entityManager->getRepository("\\Ousse\\Entite\\Silo")
            ->findOneBy(array("id" => $id));

        return $silo;
    }

    /**
     * @param int $idSilo
     * @param stdClass $jsonObject
     */
    public function addCoffres($idSilo, $jsonObject)
    {
        $silo = $this->getSilo($idSilo);
        if($silo === null)
        {
            throw new \InvalidArgumentException("Le silo demandé n'existe pas.");
        }

        if(is_array($jsonObject))
        {
            $this->addCoffresTo($silo, $jsonObject);
        }
        else
        {
            $this->addCoffreTo($silo, $jsonObject);
        }

        $this->entityManager->flush();
    }

    protected function addCoffresTo(Silo $silo, array $jsonObject)
    {
        foreach($jsonObject as $jsonCoffre)
        {
            if($jsonCoffre instanceof StdClass)
            {
                $this->addCoffreTo($silo, $jsonCoffre);
            }
            else
            {
                throw new \Exception("l'attribut coffres ne contient pas une liste valide.");
            }
        }
    }

    protected function addCoffreTo(Silo $silo, StdClass $jsonObject)
    {
        $coffre = $this->getCoffre($jsonObject->x, $jsonObject->y, $jsonObject->z);

        if($coffre === null)
        {
            $coffre = new Coffre($jsonObject);
            $this->entityManager->persist($coffre);
        }

        $coffre->setSilo($silo);

        if(isset($jsonObject->itemStacks) && is_array($jsonObject->itemStacks))
        {
            $this->addItemStacksTo($coffre, $jsonObject->itemStacks);
        }
    }

    /**
     * @param $x
     * @param $y
     * @param $z
     * @return null|Coffre
     */
    public function getCoffre($x, $y, $z)
    {
        $coffre = $this->entityManager  ->getRepository("\\Ousse\\Entite\\Coffre")
            ->findOneBy(array(  "x" => $x,
                "y" => $y,
                "z" => $z,));

        return $coffre;
    }

    public function getCoffres($idSilo)
    {
        $coffres = $this->entityManager  ->getRepository("\\Ousse\\Entite\\Coffre")
            ->findBy(array("silo" => $idSilo));

        return $coffres;
    }

    public function addItemStacks($x, $y, $z, $jsonObject)
    {
        $coffre = $this->getCoffre($x, $y, $z);
        if($coffre === null)
        {
            throw new \InvalidArgumentException("Impossible de trouver un coffre à la position $x, $y, $z");
        }

        if(is_array($jsonObject))
        {
            $this->addItemStacksTo($coffre, $jsonObject);
        }
        else
        {
            $this->addItemStackTo($coffre, $jsonObject);
        }

        $this->entityManager->flush();
    }

    public function getItemStacks($x, $y, $z)
    {
        $itemStacks = null;
        $coffre = $this->getCoffre($x, $y, $z);
        if($coffre !== null)
        {
            $itemStacks = $this->entityManager->getRepository("\\Ousse\\Entite\\ItemStack")
                ->findBy(array("coffre" => $coffre->getId()));
        }

        return $itemStacks;
    }

    protected function addItemStacksTo(Coffre $coffre, array $jsonObject)
    {
        foreach($jsonObject as $jsonItemStack)
        {
            if($jsonItemStack instanceof StdClass)
            {
                $this->addItemStackTo($coffre, $jsonItemStack);
            }
            else
            {
                throw new \Exception("l'attribut itemStacks contient une liste avec des données invalides.");
            }
        }
    }

    protected function addItemStackTo(Coffre $coffre, StdClass $jsonObject)
    {
        $item = null;
        if(isset($jsonObject->item))
        {
            $item = $this->getOraddItem($jsonObject->item);
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

    /**
     * @param stdClass $jsonObject
     * @throws \Exception
     */
    public function addItems($jsonObject)
    {
        if(is_array($jsonObject))
        {
            $this->getOraddItems($jsonObject);
        }
        else
        {
            $this->getOraddItem($jsonObject);
        }

        $this->entityManager->flush();
    }

    protected function getOraddItems(array $jsonObject)
    {
        foreach($jsonObject as $jsonItem)
        {
            if($jsonItem instanceof StdClass)
            {
                $this->getOraddItem($jsonItem);
            }
            else
            {
                throw new \Exception("l'attribut coffres ne contient pas une liste valide.");
            }
        }
    }

    /**
     * @param $jsonObject
     * @return Item
     */
    protected function getOraddItem($jsonObject)
    {
        $data = (isset($jsonObject->data)) ? $jsonObject->data: 0;
        $idItem = (isset($jsonObject->idItem)) ? $jsonObject->idItem: -1;

        $item = $this->getItem($idItem, $data);
        if($item === null)
        {
            $item = new Item($jsonObject);
            $this->entityManager->persist($item);
        }

        return $item;
    }

    /**
     * @param $idItem
     * @param $data
     * @return null|Item
     */
    public function getItem($idItem, $data)
    {
        $item = $this->entityManager->getRepository("\\Ousse\\Entite\\Item")
            ->findOneBy(array("idItem" => $idItem,
                              "data"   => $data));

        return $item;
    }



    public static function jsonDecode($jsonString)
    {
        $retour = json_decode("{}");

        if(!empty($jsonString))
        {
            $jsonObject = json_decode($jsonString);
            if(json_last_error() !== JSON_ERROR_NONE)
            {
                throw new \Exception("Erreur Json. Code d'erreur :". json_last_error());
            }
            else
            {
                $retour = $jsonObject;
            }
        }

        return $retour;
    }
}