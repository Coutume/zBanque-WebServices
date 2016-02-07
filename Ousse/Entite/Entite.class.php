<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 22/01/2016
 * Time: 19:27
 */

namespace Ousse\Entite;

/**
 * Classe abstraite représentant une entité
 * Class Entite
 * @package Ousse\Entite
 */
class Entite implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $attributs = get_object_vars($this);

        // Suppression des variables Doctrine
        foreach ($attributs as $nom => $valeur)
        {
            if(substr_compare($nom, "__", 0, 2) == 0)
            {
                unset($attributs[$nom]);
            }
        }
        return $attributs;
    }
}