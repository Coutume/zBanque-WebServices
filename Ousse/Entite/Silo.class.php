<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 21/01/2016
 * Time: 21:49
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use stdClass;

/**
 * Class Silo
 * @package Ousse\Entite
 * @Entity @Table(name="silos")
 */
class Silo extends Entite
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
    protected $x;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $z;

    /**
     * @var Item
     * @ManyToOne(targetEntity="Item", fetch="EAGER")
     * @JoinColumn(name="itemPrincipal", referencedColumnName="id", nullable=true)
     * @S
     */
    protected $itemPrincipal;

    /**
     * @var Coffre[]
     * @OneToMany(targetEntity="Coffre",mappedBy="silo", fetch="EAGER")
     */
    protected $coffres;

    public function __construct(StdClass $jsonObjet = null)
    {
        // Ces propriétés ne prennent pas le contenu de l'objet Json
        $this->coffres = array();
        $this->itemPrincipal = null;

        if($jsonObjet != null)
        {
            if(isset($jsonObjet->x) && isset($jsonObjet->z))
            {
                $this->x = $jsonObjet->x;
                $this->z = $jsonObjet->z;
            }
            else
            {
                throw new \Exception("Les propriétés nécessaires à l'entité Silo sont introuvables dans l'objet Json.");
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
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * @param int $z
     */
    public function setZ($z)
    {
        $this->z = $z;
    }

    /**
     * @return Item
     */
    public function getItemPrincipal()
    {
        return $this->itemPrincipal;
    }

    /**
     * @param Item $itemPrincipal
     */
    public function setItemPrincipal($itemPrincipal)
    {
        $this->itemPrincipal = $itemPrincipal;
    }

    /**
     * @return Coffre[]
     */
    public function getCoffres()
    {
        return $this->coffres;
    }

    /**
     * @param Coffre[] $coffres
     */
    public function setCoffres($coffres)
    {
        $this->coffres = $coffres;
    }
}