<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 15/01/2016
 * Time: 23:22
 */

namespace Ousse\Map;


class MapParams implements \JsonSerializable
{
    /**
     * @var integer[] liste des résolutions (nombre de mètres par pixel) pour chaque nv de zoom
     */
    private $resolutions;

    /**
     * @var integer taille en pixel des tuiles
     */
    private $tailleTuiles;

    /**
     * @var double[] Coordonnées x,y du point d'origine de la map
     */
    private $pointOrigine;

    /**
     * @var double[] Coordonnées x,y du point d'origine de la map
     */
    private $coordMax;

    public function __construct($filepath = './config.json')
    {
        if(file_exists($filepath))
        {
            $params = file_get_contents($filepath);
            $objet = json_decode($params);

            $this->resolutions = $objet->resolutions;
            $this->tailleTuiles = $objet->tailleTuiles;
            $this->pointOrigine = $objet->pointOrigine;
            $this->coordMax = $objet->coordMax;
        }
    }

    /**
     * @return double[]
     */
    public function getPointOrigine()
    {
        return $this->pointOrigine;
    }

    /**
     * @param double[] $pointOrigine
     */
    public function setPointOrigine($pointOrigine)
    {
        $this->pointOrigine = $pointOrigine;
    }

    /**
     * @return int
     */
    public function getTailleTuiles()
    {
        return $this->tailleTuiles;
    }

    /**
     * @param int $tailleTuiles
     */
    public function setTailleTuiles($tailleTuiles)
    {
        $this->tailleTuiles = $tailleTuiles;
    }

    /**
     * @return integer[]
     */
    public function getResolutions()
    {
        return $this->resolutions;
    }

    /**
     * @param integer $resolution
     */
    public function addResolutions($resolution)
    {
        $this->resolutions[] = $resolution;
    }

    /**
     * @return \double[]
     */
    public function getCoordMax()
    {
        return $this->coordMax;
    }

    /**
     * @param \double[] $coordMax
     */
    public function setCoordMax($coordMax)
    {
        $this->coordMax = $coordMax;
    }

    public function reset()
    {
        $this->resolutions = array();

        $this->pointOrigine = array();

        $this->tailleTuiles = false;
    }

    public function save($filepath = './config.json')
    {
        $json = json_encode($this);

        echo json_last_error(). ', '. json_last_error_msg();
        echo $json;

        file_put_contents($filepath, $json);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}