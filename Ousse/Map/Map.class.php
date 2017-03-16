<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 11/01/2016
 * Time: 19:22
 */

namespace Ousse\Map;


use Doctrine\ORM\EntityManager;
use Ousse\Entite\Banque;
use Ousse\Entite\BlocTuile;
use Ousse\Manager\BanqueManager;
use Ousse\Manager\BlocTuileManager;

class Map
{
    /**
     * @var \PDO Connexion à la base contenant la map
     */
    //private $connexion;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BlocTuileManager
     */
    private $blocTuileManager;

    /**
     * @var Banque
     */
    private $_currentBanque;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->blocTuileManager = new BlocTuileManager($this->entityManager);

        $this->_currentBanque = null;
    }

    /**
     * @param $x
     * @param $y
     * @return bool|BlocTuile
     */
    public function getBlocTuileAt($x, $y)
    {
        $bloc = $this->blocTuileManager->getByPos($x, $y);

        if($bloc !== null && count($bloc) > 0)
        {
            return $bloc[0];
        }
        else
        {
            return false;
        }
    }

    /**
     * Génère les tuiles pour toutes les banques ayant une configuration renseignée
     */
    public function genererTuilesToutesBanques()
    {
        $bm = new BanqueManager($this->entityManager);
        $banques = $bm->getAllWithConfig();

        foreach ($banques as $banque)
        {
            $this->setCurrentBanque($banque);
            $this->genererTuiles();
        }
    }

    /**
     * Génère les tuiles pour la banque courante
     * @throws \Exception Si aucune banque courante n'est définie
     */
    public function genererTuiles()
    {
        set_time_limit(300); // 5 minutes pour générer la carte d'une banque

        if($this->getCurrentBanque() == null)
        {
            throw new \Exception("Aucune banque courante n'est sélectionnée.");
        }

        $taille = $this->getCurrentBanque()->getConfig()->getTaille();
        $nbBlocsDepart = $this->getCurrentBanque()->getConfig()->getNbBlocs();
        $zoomMax = $this->getCurrentBanque()->getConfig()->getZoom();

        $this->getCurrentBanque()->getConfig()->reset();

        $this->genererTuilesPourZoom(0, $taille, $nbBlocsDepart);

        for($zoom = 1; $zoom < $zoomMax; $zoom++)
        {
            $nbBlocs = $nbBlocsDepart / (pow(2, $zoom));

            if($nbBlocs >= 1)
            {
                $this->genererTuilesPourZoom($zoom, $taille, $nbBlocs);
            }
            else
            {
                break;
            }
        }

        $this->getCurrentBanque()->getConfig()->setCoordMin($this->getMinPos());
        $this->getCurrentBanque()->getConfig()->setCoordMax($this->getMaxPos());
        $this->entityManager->flush();
    }

    public function genererTuilesPourZoom($zoom, $taille, $nbBlocs)
    {
        $posMini = $this->getMinPos();
        $posMaxi = $this->getMaxPos();
        $tailleBloc = $taille / $nbBlocs;
        $nomBanque = $this->getCurrentBanque()->getNom();

        $this->creerDossier($zoom, $nomBanque);

        $x1 = 0;
        $y1 = 0;
        for($i = $posMini['x']; $i < $posMaxi['x']; $i += $nbBlocs)
        {
            for($j = $posMini['y']; $j < $posMaxi['y']; $j += $nbBlocs)
            {
                $im = $this->genererTuile($i, $j, $nbBlocs, $taille, $tailleBloc);

                $nom = "bloc_".  $x1. "_". $y1. ".png";
                $this->enregistrerImage($im, "Ousse/tuiles/$nomBanque/$zoom/$nom");

                $y1++;
            }

            $x1++;
            $y1 = 0;
        }

        $this->getCurrentBanque()->getConfig()->addResolution($nbBlocs / $taille);
    }

    private function creerDossier($zoom, $nomBanque)
    {
        if (!file_exists("Ousse/tuiles/$nomBanque/$zoom"))
        {
            mkdir("Ousse/tuiles/$nomBanque/$zoom", 0777, true);
        }
    }

    public function genererTuile($xOrigine, $yOrigine, $nbBlocs, $tailleTuile, $tailleBloc)
    {
        $im = imagecreatetruecolor($tailleTuile, $tailleTuile);

        for($x = $xOrigine; $x < $xOrigine + $nbBlocs; $x++)
        {
            for($y = $yOrigine; $y < $yOrigine + $nbBlocs; $y++)
            {
                $bloc = $this->getBlocTuileAt($x, $y);

                $image = null;
                if($bloc !== false)
                {
                    $image = $this->genererImage($bloc, $tailleBloc);
                }
                else
                {
                    $image = imagecreatetruecolor($tailleBloc, $tailleBloc);
                    $blanc = imagecolorallocate($image, 255, 255, 255);
                    imagefill($image, 0, 0, $blanc);
                }

                imagecopy($im, $image, ($x - $xOrigine) * $tailleBloc,
                    ($y - $yOrigine) * $tailleBloc, 0, 0, $tailleBloc, $tailleBloc);

                imagedestroy($image);
            }
        }

        return $im;
    }

    private function genererImage(BlocTuile $bloc, $taille)
    {
        $im = -1;
        if(file_exists("Ousse/theme/{$bloc->getBloc()}.png"))
        {
            $im = imagecreatefrompng("Ousse/theme/{$bloc->getBloc()}.png");
            $im = imagescale($im, $taille, $taille, IMG_NEAREST_NEIGHBOUR);
        }
        else
        {
            $im = imagecreatetruecolor($taille, $taille);
        }

        return $im;

    }

    private function enregistrerImage($image, $chemin)
    {
        imagepng($image, $chemin);

        imagedestroy($image);
    }

    /**
     * @return bool|double[]
     */
    public function getMinPos()
    {
        $pos = $this->entityManager->getConnection()->query("SELECT MIN(x) as x, MIN(z) as y FROM cases
                                                              where banque = '{$this->_currentBanque->getNom()}'");

        if($pos !== null && $pos !== false && $pos->rowCount() > 0)
        {
            $posAssoc = $pos->fetchAll(\PDO::FETCH_ASSOC);
            return $posAssoc[0];
        }

        return false;
    }

    /**
     * @return bool|double[]
     */
    public function getMaxPos()
    {
        $pos = $this->entityManager->getConnection()->query("SELECT MAX(x) as x, MAX(z) as y FROM cases
                                                              where banque = '{$this->_currentBanque->getNom()}'");

        if($pos !== null && $pos !== false && $pos->rowCount() > 0)
        {
            $posAssoc = $pos->fetchAll(\PDO::FETCH_ASSOC);
            return $posAssoc[0];
        }

        return false;
    }

    /**
     * @return Banque
     */
    public function getCurrentBanque()
    {
        return $this->_currentBanque;
    }

    /**
     * @param Banque $currentBanque
     */
    public function setCurrentBanque(Banque $currentBanque)
    {
        $this->_currentBanque = $currentBanque;
    }
}