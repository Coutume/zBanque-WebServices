<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 21/01/2016
 * Time: 21:52
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use stdClass;

/**
 * Class Coffre
 * @package Ousse\Entite
 * @Entity @Table(name="coffres",uniqueConstraints={@UniqueConstraint(name="position_idx", columns={"x","y","z","map"})})
 */
class Coffre extends Entite
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
    protected $y;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $z;

    /**
     * @var string le nom de la banque
     * @Column(type="string", length=42, nullable=false)
     */
    protected $map;

    /**
     * @var Silo
     * @ManyToOne(targetEntity="Silo", inversedBy="coffres", fetch="EAGER")
     * @JoinColumn(name="silo", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $silo;

    /**
     * @var ItemStack[]
     * @OneToMany(targetEntity="ItemStack", mappedBy="coffre")
     */
    protected $itemStacks;

    public function __construct(StdClass $jsonObject = null)
    {
        $this->silo = null;
        $this->itemStacks = array();

        if($jsonObject != null)
        {
            if(isset($jsonObject->x) && isset($jsonObject->z) && isset($jsonObject->y) && isset($jsonObject->map))
            {
                $this->x = $jsonObject->x;
                $this->z = $jsonObject->z;
                $this->y = $jsonObject->y;
                $this->map = $jsonObject->map;
            }
            else
            {
                throw new \Exception("Les propriétés nécessaires à l'entité Coffre sont introuvables dans l'objet Json.");
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
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
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
     * @return string
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param string $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return Silo
     */
    public function getSilo()
    {
        return $this->silo;
    }

    /**
     * @param Silo $silo
     */
    public function setSilo($silo)
    {
        $this->silo = $silo;
    }

    /**
     * @return ItemStack[]
     */
    public function getItemStacks()
    {
        return $this->itemStacks;
    }

    /**
     * @param ItemStack[] $itemStacks
     */
    public function setItemStacks($itemStacks)
    {
        $this->itemStacks = $itemStacks;
    }
}