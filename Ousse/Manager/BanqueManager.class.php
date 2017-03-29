<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 03/10/2016
 * Time: 21:15
 */

namespace Ousse\Manager;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Ousse\Entite\Banque;
use Ousse\Entite\MapBanqueConfig;

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
     * Retourne toutes les banques ayant une configuration
     * @return Banque[]
     */
    public function getAllWithConfig()
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('b')
            ->from('\\Ousse\\Entite\\Banque', 'b')
            ->where('b.config is not NULL');

        $q = $qb->getQuery();
        $resultat = $q->getResult();
        return $resultat;
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
            $banque = $this->add($jsonObject);
        }

        return $banque;
    }

    public function add($jsonObject)
    {
        $banque = new Banque($jsonObject);
        $this->entityManager->persist($banque);
        $this->entityManager->flush();

        return $banque;
    }

    public function setConfig(Banque $banque, MapBanqueConfig $config)
    {
        $banque->setConfig($config);

        $this->entityManager->persist($config);
        $this->entityManager->flush();
    }
}