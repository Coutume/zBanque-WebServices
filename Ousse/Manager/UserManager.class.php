<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 13/02/2016
 * Time: 13:02
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;
use Ousse\Entite\User;

class UserManager
{
    protected $entitymanager;

    public function __construct(EntityManager $manager)
    {
        $this->entitymanager = $manager;
    }

    /**
     * @param $user
     * @return null|User
     */
    public function get($user)
    {
        $utilisateur = $this->entitymanager->getRepository("\\Ousse\\Entite\\User")
            ->findOneBy(array("pseudo" => $user));

        return $utilisateur;
    }

    public function add(array $userInfo)
    {
        $user = new User($userInfo);
        $this->entitymanager->persist($user);

        $this->entitymanager->flush();
    }
}