<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 05/10/2016
 * Time: 20:07
 */

namespace Ousse\Entite;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Représente une configuration à appliquer pour la génération et le rendu d'une carte openlayers.
 * Seul les champs $taille, $nbBlocs et $zoom sont nécessaires
 * pour lancer une génération de la carte, les autres champs seront
 * automatiquement remplis.
 * @Entity
 * @Table(name="mapBanqueConfig")
 * @package Ousse\Entite
 */
class MapBanqueConfig extends Entite
{
    /**
     * Identifiant de la configuration
     * @var integer
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * La taille en pixel d'une tuile. Une tuile équivaut à un morceau de la carte,
     * enregistré en tant qu'image.
     * Il est conseillé de mettre une puissance de 2 pour que la génération des tuiles
     * fonctionnent correctement.
     * @var integer
     * @Column(type="integer")
     */
    protected $taille;

    /**
     * Le niveau maximum de zoom de la carte.
     * @var integer
     * @Column(type="integer")
     */
    protected $zoom;

    /**
     * Le nombre de blocs par tuile pour le premier niveau de zoom
     * @var integer
     * @Column(type="integer")
     */
    protected $nbBlocs;

    /**
     * Position x minimum de la carte. Aucun bloc ne sera rendu
     * en dessous de cette valeur
     * @var integer
     * @Column(type="integer", nullable=true)
     */
    protected $xMin;

    /**
     * Position y minimum de la carte. Aucun bloc ne sera rendu
     * en dessous de cette valeur
     * @var integer
     * @Column(type="integer", nullable=true)
     */
    protected $yMin;

    /**
     * Position x maximum de la carte. Aucun bloc ne sera rendu
     * au delà de cette valeur
     * @var integer
     * @Column(type="integer", nullable=true)
     */
    protected $xMax;

    /**
     * Position y maximum de la carte. Aucun bloc ne sera rendu
     * au delà de cette valeur
     * @var integer
     * @Column(type="integer", nullable=true)
     */
    protected $yMax;

    /**
     * Tableau de résolutions, trié de la plus petite à la plus grande.
     * Une résolution correspond au nombre de blocs pour une pixel.
     * Exemple : 0.5 = un bloc pour 2 pixels.
     * @var array
     * @Column(type="json_array", nullable=true)
     */
    protected $resolutions;


    public function __construct($jsonObject)
    {
        $this->resolutions = array();

        if(isset($jsonObject->taille))
        {
            $this->taille = $jsonObject->taille;

            if(isset($jsonObject->zoom))
            {
                $this->zoom = $jsonObject->zoom;

                if(isset($jsonObject->nbBlocs))
                {
                    $this->nbBlocs = $jsonObject->nbBlocs;
                }
                else
                {
                    throw new \Exception("La propriété nbBlocs nécessaire à l'entité Config est introuvable dans l'objet Json.");
                }
            }
            else
            {
                throw new \Exception("La propriété zoom nécessaire à l'entité Config est introuvable dans l'objet Json.");
            }

        }
        else
        {
            throw new \Exception("La propriété taille nécessaire à l'entité Config est introuvable dans l'objet Json.");
        }
    }

    /**
     * @return int
     * @see MapBanqueConfig::$id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getXMin()
    {
        return $this->xMin;
    }

    /**
     * @param int $xMin
     */
    public function setXMin($xMin)
    {
        $this->xMin = $xMin;
    }

    /**
     * @return int
     */
    public function getYMin()
    {
        return $this->yMin;
    }

    /**
     * @param int $yMin
     */
    public function setYMin($yMin)
    {
        $this->yMin = $yMin;
    }

    /**
     * @return int
     */
    public function getXMax()
    {
        return $this->xMax;
    }

    /**
     * @param int $xMax
     */
    public function setXMax($xMax)
    {
        $this->xMax = $xMax;
    }

    /**
     * @return int
     */
    public function getYMax()
    {
        return $this->yMax;
    }

    /**
     * @param int $yMax
     */
    public function setYMax($yMax)
    {
        $this->yMax = $yMax;
    }

    /**
     * @return int
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * @param int $taille
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;
    }

    /**
     * @return array
     */
    public function getResolutions()
    {
        return $this->resolutions;
    }

    /**
     * @param array $resolutions
     */
    public function setResolutions($resolutions)
    {
        $this->resolutions = $resolutions;
    }

    /**
     * @param double $resolution
     */
    public function addResolution($resolution)
    {
        $this->resolutions[] = $resolution;
    }

    /**
     * @return int
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * @param int $zoom
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }

    /**
     * @return int
     */
    public function getNbBlocs()
    {
        return $this->nbBlocs;
    }

    /**
     * @param int $nbBlocs
     */
    public function setNbBlocs($nbBlocs)
    {
        $this->nbBlocs = $nbBlocs;
    }
    public function setCoordMin(array $pos)
    {
        $this->setXMin($pos["x"]);
        $this->setYMin($pos["y"]);
    }

    public function setCoordMax(array $pos)
    {
        $this->setXMax($pos["x"]);
        $this->setYMax($pos["y"]);
    }

    public function reset()
    {
        $this->resolutions = array();
        $this->xMin = null;
        $this->yMin = null;
        $this->xMax = null;
        $this->yMax = null;
    }

    function jsonSerialize()
    {
        $attributs = parent::jsonSerialize();

        $attributs["coordMin"] = ["x" => $this->getXMin(), "y" => $this->getYMin()];
        $attributs["coordMax"] = ["x" => $this->getXMax(), "y" => $this->getYMax()];

        return $attributs;
    }
}