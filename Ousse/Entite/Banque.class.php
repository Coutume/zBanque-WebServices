<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 12/02/2016
 * Time: 23:44
 */

namespace Ousse\Entite;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use stdClass;

/**
 * Class Banque
 * @package Ousse\Entite
 * @Entity
 */
class Banque extends Entite
{
    /**
     * @var string nom de la banque
     * @Id
     * @Column(type="string")
     */
    protected $nom;

    /**
     * @var int position x du centre de la banque
     * @Column(type="integer")
     */
    protected $x;

    /**
     * @var int position y du centre de la banque
     * @Column(type="integer")
     */
    protected $y;

    /**
     * @var int position z du centre de la banque
     * @Column(type="integer")
     */
    protected $z;

    /**
     * @var string le nom de la map où se trouve la banque
     * @Column(type="string", length=42, nullable=false)
     */
    protected $map;

    public function __construct(StdClass $jsonObject)
    {
        if($jsonObject != null)
        {
            if(isset($jsonObject->nom))
            {
                $this->nom = $jsonObject->nom;

                if(isset($jsonObject->x))
                {
                    $this->x = $jsonObject->x;

                    if(isset($jsonObject->y))
                    {
                        $this->y = $jsonObject->y;

                        if(isset($jsonObject->z))
                        {
                            $this->z = $jsonObject->z;
                            if(isset($jsonObject->map))
                            {
                                $this->map = $jsonObject->map;
                            }
                            else
                            {
                                throw new \Exception("La propriété map nécessaire à l'entité Banque est introuvable dans l'objet Json.");
                            }
                        }
                        else
                        {
                            throw new \Exception("La propriété z nécessaire à l'entité Banque est introuvable dans l'objet Json.");
                        }

                    }
                    else
                    {
                        throw new \Exception("La propriété y nécessaire à l'entité Banque est introuvable dans l'objet Json.");
                    }
                }
                else
                {
                    throw new \Exception("La propriété x nécessaire à l'entité Banque est introuvable dans l'objet Json.");
                }
            }
            else
            {
                throw new \Exception("La propriété idItem nécessaire à l'entité Banque est introuvable dans l'objet Json.");
            }
        }
    }

}