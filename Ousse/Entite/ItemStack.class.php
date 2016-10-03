<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 21/01/2016
 * Time: 22:00
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use stdClass;

/**
 * Class ItemStack
 * @package Ousse\Entite
 * @Entity @Table(name="item_stack")
 */
class ItemStack extends Entite
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @var Item
     * @ManyToOne(targetEntity="Item")
     * @JoinColumn(name="itemPrincipal", referencedColumnName="id", nullable=false)
     */
    protected $item;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $montant;

    /**
     * @var Coffre
     * @ManyToOne(targetEntity="Coffre", inversedBy="itemStacks")
     * @JoinColumn(name="coffre", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $coffre;

    public function __construct(StdClass $jsonObject)
    {
        $this->montant = (isset($jsonObject->montant)) ? $jsonObject->montant : 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param int $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return Coffre
     */
    public function getCoffre()
    {
        return $this->coffre;
    }

    /**
     * @param Coffre $coffre
     */
    public function setCoffre($coffre)
    {
        $this->coffre = $coffre;
    }

    function jsonSerialize()
    {
        $attributs = parent::jsonSerialize();
        unset($attributs['coffre']); // Suppression de l'attribut afin d'éviter les références circulaires

        return $attributs;
    }


}