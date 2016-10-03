<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 03/10/2016
 * Time: 21:15
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;
use Ousse\Entite\Banque;

class BanqueManager
{
    /**
     * Permet de gérer la persistance et la mise à jour
     * des entités Doctrine
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $banques = $this->entityManager->getRepository("\\Ousse\\Entite\\Banque")
            ->findAll();

        return $banques;
    }

    /**
     * Renvoie les infos sur la banque nommée $nom
     * @param $nom string le nom de la banque
     * @return null|Banque
     */
    public function getByName($nom)
    {
        $banque = $this->entityManager->getRepository("\\Ousse\\Entite\\Banque")
            ->findOneBy(array("nom" => $nom));

        return $banque;
    }

    /**
     * @param $nom
     */
    public function resetByName($nom)
    {
        $reqResetBanque = $this->entityManager->createQuery("Delete \\Ousse\\Entite\\Silo silo Where silo.banque IN".
            " (Select banque From \\Ousse\\Entite\\Banque banque Where banque.nom = '$nom')");
        $reqResetBanque->execute();

        // Pas de retour. En cas de problème, une exception est levée par execute()
    }

    /**
     * Ajoute une nouvelle banque, ou renvoie la banque
     * ayant le nom défini dans l'objet JSON si existante
     * @param $jsonObject
     * @return null|Banque
     */
    public function getOradd($jsonObject)
    {
        $nom = (isset($jsonObject->nom)) ? $jsonObject->nom: null;

        $banque = $this->getByName($nom);
        if($banque === null)
        {
            $banque = new Banque($jsonObject);
            $this->entityManager->persist($banque);
            $this->entityManager->flush();
        }

        return $banque;
    }
}