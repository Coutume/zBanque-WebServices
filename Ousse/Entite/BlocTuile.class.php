<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 11/01/2016
 * Time: 19:39
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Représente une tuile correspondant à un bloc sur une carte
 * @Entity @Table(name="cases")
 * @package Ousse\Entite
 */
class BlocTuile extends Entite
{
    /**
     * @var integer
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
     * @var string
     * @Column(type="string", length=255)
     */
    protected $bloc;

    /**
     * @var Banque l'objet Banque
     * @ManyToOne(targetEntity="Banque")
     * @JoinColumn(name="banque", referencedColumnName="nom", nullable=false,onDelete="CASCADE")
     */
    protected $banque;

    /**
     * @var string Le nom de la banque
     * @Column(name="banque", type="string", length=255)
     */
    protected $nomBanque;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getBloc()
    {
        return $this->bloc;
    }

    /**
     * @param mixed $bloc
     */
    public function setBloc($bloc)
    {
        $this->bloc = $bloc;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }
}