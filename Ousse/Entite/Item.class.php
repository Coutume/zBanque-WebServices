<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 21/01/2016
 * Time: 21:55
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use stdClass;

/**
 * Class Item
 * @package Ousse\Entite
 * @Entity @Table(name="items",uniqueConstraints={@UniqueConstraint(name="item_idx", columns={"idItem", "data"})})
 */
class Item extends Entite
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $idItem;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $data;

    /**
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $nom;

    public function __construct(StdClass $jsonObject)
    {
        $this->data = (isset($jsonObject->data)) ? $jsonObject->data: 0;
        $this->nom  = (isset($jsonObject->nom))  ? $jsonObject->nom: '';

        if($jsonObject != null)
        {
            if(isset($jsonObject->idItem))
            {
                $this->idItem = $jsonObject->idItem;
            }
            else
            {
                throw new \Exception("La propriété idItem nécessaire à l'entité Item est introuvable dans l'objet Json.");
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * @param int $idItem
     */
    public function setIdItem($idItem)
    {
        $this->idItem = $idItem;
    }

    /**
     * @return int
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
}