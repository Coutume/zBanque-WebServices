<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 13/02/2016
 * Time: 11:39
 */

namespace Ousse\WebService;


use Doctrine\ORM\EntityManager;
use Ousse\Manager\UserManager;

class WebAuth
{
    /**
     * @var UserManager
     */
    protected $manager;

    public function __construct(EntityManager $userManager)
    {
        $this->manager = new UserManager($userManager);
    }

    /**
     * @param $user
     * @param $pwd
     * @return bool|\Ousse\Entite\User
     */
    public function login($user, $pwd)
    {
        $logged = false;

        $utilisateur = $this->manager->get($user);

        if($utilisateur !== null)
        {
            $logged = password_verify($pwd, $utilisateur->getMdp());
            if($logged)
            {
                return $utilisateur;
            }
        }

        return false;
    }
}