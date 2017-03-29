<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 22/01/2016
 * Time: 11:46
 */

namespace Ousse\Manager;
use Doctrine\ORM\EntityManager;
use Ousse\Entite\Banque;
use Ousse\Entite\Item;
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

    /**
     * @var BanqueManager
     */
    private $_banqueManager;

    /**
     * @var ItemStackManager
     */
    private $_itemStackManager;

    /**
     * @var CoffreManager
     */
    private $_coffreManager;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
        $this->_banqueManager = new BanqueManager($this->entityManager);
        $this->_itemStackManager = new ItemStackManager($this);
        $this->_coffreManager = new CoffreManager($this);
    }

    public function getEntities($entite, array $conditions)
    {
        $entites = $this->entityManager->getRepository("\\Ousse\\Entite\\$entite")->findBy($conditions);

        return $entites;
    }

    /**
     * Renvoie les infos sur la banque nommée $nom
     * @param $nom string le nom de la banque
     * @return null|Banque
     */
    public function getBanque($nom)
    {
        return $this->_banqueManager->getByName($nom);
    }

    public function resetBanque($nom)
    {
        $this->_banqueManager->resetByName($nom);
    }

    /**
     * Ajoute une nouvelle banque, ou renvoie la banque
     * ayant le nom défini dans l'objet JSON si existante
     * @param $jsonObject
     * @return null|Banque
     */
    protected function getOraddBanque($jsonObject)
    {
        return $this->_banqueManager->getOradd($jsonObject);
    }

    /**
     * Ajoute le ou les silos définis dans l'objet json
     * @param stdClass $jsonObject
     */
    public function addMany($jsonObject)
    {
        if(!is_array($jsonObject))
        {
            $this->add($jsonObject);
        }
        else
        {
            foreach($jsonObject as $json)
            {
                $this->add($json);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * Ajoute le silo défini dans l'objet JSON
     * ATTENTION : cette méthode ne fait pas de flush(),
     * veillez donc à l'exécuter afin que Doctrine sauvegarde l'entité
     * en base
     * @param stdClass $jsonObject
     * @throws \Exception
     */
    protected function add(stdClass $jsonObject)
    {
        if(isset($jsonObject->banque))
        {
            $banque = $this->getOraddBanque($jsonObject->banque);

            $silo = $this->getBypos($jsonObject->x, $jsonObject->z, $banque->getNom());

            if($silo == null)
            {
                $silo = new Silo($jsonObject);
            }

            if(isset($jsonObject->itemPrincipal))
            {
                $silo->setItemPrincipal($jsonObject->itemPrincipal);
            }

            $silo->setBanque($banque);
            $this->entityManager->persist($silo);

            if(isset($jsonObject->coffres) && is_array($jsonObject->coffres))
            {
                $this->_coffreManager->addManyTo($silo, $jsonObject->coffres);
            }

            if(isset($jsonObject->itemPrincipal))
            {
                $item = $this->getOraddItem($jsonObject->itemPrincipal);
                $silo->setItemPrincipal($item);
            }
        }
        else
        {
            throw new \Exception("Impossible de trouver les informations sur la banque dans le silo.", -1);
        }

    }

    /**
     * Récupère un silo selon son id
     * @param $id
     * @return null|Silo
     */
    public function get($id)
    {
        $silo = $this->entityManager->getRepository("\\Ousse\\Entite\\Silo")
            ->findOneBy(array("id" => $id));

        return $silo;
    }

    /**
     * @param $x
     * @param $z
     * @param $nomBanque
     * @return null|Silo
     */
    public function getBypos($x, $z, $nomBanque)
    {
        $silo = $this->entityManager->getRepository("\\Ousse\\Entite\\Silo")
            ->findOneBy(array(  "x" => $x,
                                "z" => $z,
                                "banque" => $nomBanque));

        return $silo;
    }

    public function delete($silo)
    {
        $this->entityManager->remove($silo);

        $this->entityManager->flush();
    }

    public function deleteMany($jsonSilos)
    {
        $supprimee = 0;
        foreach ($jsonSilos as $jsonSilo)
        {
            if(isset($jsonSilo->nomBanque) && isset($jsonSilo->x) && isset($jsonSilo->x))
            {
                $silo = $this->getBypos($jsonSilo->x, $jsonSilo->z, $jsonSilo->nomBanque);

                if($silo != null)
                {
                    $this->delete($silo);
                    $supprimee++;
                }
            }
        }

        return $supprimee;
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

    public function getOraddItems(array $jsonObject)
    {
        foreach($jsonObject as $jsonItem)
        {
            if($jsonItem instanceof stdClass)
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
    public function getOraddItem($jsonObject)
    {
        $data = (isset($jsonObject->data)) ? $jsonObject->data: 0;
        $idItem = (isset($jsonObject->idItem)) ? $jsonObject->idItem: -1;

        $item = $this->getItem($idItem, $data);
        if($item === null)
        {
            $item = new Item($jsonObject);
            $this->entityManager->persist($item);
            $this->entityManager->flush($item);
        }

        return $item;
    }

    /**
     * Récupère un item
     * @param $idItem int l'identifiant numéraire Minecraft de l'item
     * @param $data int l'attribut donnée Minecraft de l'item
     * @return null|Item l'entité si l'item existe, null sinon
     */
    public function getItem($idItem, $data)
    {
        $item = $this->entityManager->getRepository("\\Ousse\\Entite\\Item")
            ->findOneBy(array("idItem" => $idItem,
                              "data"   => $data));

        return $item;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return BanqueManager
     */
    public function getBanqueManager()
    {
        return $this->_banqueManager;
    }

    /**
     * @param BanqueManager $banqueManager
     */
    public function setBanqueManager($banqueManager)
    {
        $this->_banqueManager = $banqueManager;
    }

    /**
     * @return ItemStackManager
     */
    public function getItemStackManager()
    {
        return $this->_itemStackManager;
    }

    /**
     * @param ItemStackManager $itemStackManager
     */
    public function setItemStackManager($itemStackManager)
    {
        $this->_itemStackManager = $itemStackManager;
    }

    /**
     * @return CoffreManager
     */
    public function getCoffreManager()
    {
        return $this->_coffreManager;
    }

    /**
     * @param CoffreManager $coffreManager
     */
    public function setCoffreManager($coffreManager)
    {
        $this->_coffreManager = $coffreManager;
    }

    /**
     * Parse la chaine de caractère JSON passée en paramètre
     * @param $jsonString string la chaine de caractère JSON à parser
     * @return mixed objet JSON deserializé
     * @throws \Exception Si une erreur survient lors de la deserialization
     */
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