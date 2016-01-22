<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 22/01/2016
 * Time: 01:31
 */

/**
 * fonction de chargement automatique
 * des classes Ousse.
 * @param string $pClassName
 */
function my_autoload ($pClassName) {
    $pClassName = str_replace("\\", "/", $pClassName);
    include_once(__DIR__ . "/" . $pClassName . ".class.php");
}
spl_autoload_register("my_autoload");