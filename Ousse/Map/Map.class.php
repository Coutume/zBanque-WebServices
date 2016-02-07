<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 11/01/2016
 * Time: 19:22
 */

namespace Ousse\Map;


use Ousse\Entite\BlocTuile;

class Map
{
    /**
     * @var \PDO Connexion à la base contenant la map
     */
    private $connexion;

    /**
     * @var MapParams paramètres relatifs à cette carte
     */
    private $params;

    public function __construct()
    {
        // Ces variables évitent juste les alertes de variables non initialisées
        $host = '';
        $user = '';
        $pwd = '';
        $dbname = '';

        // Paramètres de connexion
        include_once __DIR__."/../../connexion.inc.php";
        $this->connexion = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);

        $this->params = new MapParams();
    }

    /**
     * @return BlocTuile[]
     */
    public function getAllBlocTuiles()
    {
        return $this->connexion->query("SELECT * FROM cases")->fetchAll(\PDO::FETCH_CLASS, '\Ousse\Map\BlocTuile');
    }

    /**
     * @param $x
     * @param $y
     * @return bool|BlocTuile
     */
    public function getBlocTuileAt($x, $y)
    {
        $reqBloc =$this->connexion->query("SELECT * FROM cases WHERE x = $x AND z = $y");

        if($reqBloc !== null && $reqBloc !== false)
        {
            $bloc = $reqBloc->fetchAll(\PDO::FETCH_CLASS, '\Ousse\Map\BlocTuile');

            if($bloc !== null && count($bloc) > 0)
            {
                return $bloc[0];
            }
            else
            {
                return false;
            }
        }

        return false;
    }

    public function genererTuiles($taille, $nbBlocsDepart, $zoomMax = 5)
    {
        $this->params->reset();

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

        $this->params->setTailleTuiles($taille);
        $this->params->setPointOrigine($this->getMinPos());
        $this->params->setCoordMax($this->getMaxPos());
        $this->params->save();
    }

    public function genererTuilesPourZoom($zoom, $taille, $nbBlocs)
    {
        $posMini = $this->getMinPos();
        $posMaxi = $this->getMaxPos();
        $tailleBloc = $taille / $nbBlocs;

        $this->creerDossier($zoom);

        $x1 = 0;
        $y1 = 0;
        for($i = $posMini['x']; $i < $posMaxi['x']; $i += $nbBlocs)
        {
            for($j = $posMini['y']; $j < $posMaxi['y']; $j += $nbBlocs)
            {
                $im = $this->genererTuile($i, $j, $nbBlocs, $taille, $tailleBloc);

                $nom = "bloc_".  $x1. "_". $y1. ".png";
                $this->enregistrerImage($im, "Ousse/tuiles/$zoom/$nom");

                $y1++;
            }

            $x1++;
            $y1 = 0;
        }

        $this->params->addResolutions($nbBlocs / $taille);
    }

    private function creerDossier($zoom)
    {
        if (!file_exists("Ousse/tuiles/$zoom"))
        {
            mkdir("Ousse/tuiles/$zoom", 0777, true);
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
        $pos = $this->connexion->query("SELECT MIN(x) as x, MIN(z) as y FROM cases");

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
        $pos = $this->connexion->query("SELECT MAX(x) as x, MAX(z) as y FROM cases");

        if($pos !== null && $pos !== false && $pos->rowCount() > 0)
        {
            $posAssoc = $pos->fetchAll(\PDO::FETCH_ASSOC);
            return $posAssoc[0];
        }

        return false;
    }

    /**
     * @return MapParams
     */
    public function getParams()
    {
        return $this->params;
    }
}