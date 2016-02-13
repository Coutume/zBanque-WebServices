<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 13/02/2016
 * Time: 12:47
 */

namespace Ousse\Entite;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

/**
 * Class User
 * @package Ousse\Entite
 * @Entity
 */
class User
{
    /**
     * @var string
     * @Id
     * @Column(type="string")
     */
    protected $pseudo;

    /**
     * @var string mot de passe chiffré de l'utilisateur
     * @Column(type="string")
     */
    protected $mdp;

    /**
     * @var boolean définit si l'utilisateur peut exécuter une requete POST
     * @Column(type="boolean")
     *
     */
    protected $canPost = false;

    public function __construct(array $info = [])
    {
        if(isset($info['pseudo']))
        {
            if(isset($info['mdp']))
            {
                $this->pseudo = $info['pseudo'];
                $this->mdp = password_hash($info['mdp'], PASSWORD_DEFAULT);
                $this->canPost = (isset($info['canPost']) ? $info['canPost']: false);
            }
            else
            {
                throw new \Exception("Mot de passe manquant.");
            }
        }
        else
        {
            throw new \Exception("Pseudo manquant.");
        }
    }

    /**
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param string $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return boolean
     */
    public function canPost()
    {
        return $this->canPost;
    }

    /**
     * @param boolean $canPost
     */
    public function setCanPost($canPost)
    {
        $this->canPost = $canPost;
    }
}