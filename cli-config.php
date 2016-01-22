<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 21/01/2016
 * Time: 23:34
 */

require_once "bootstrap.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);