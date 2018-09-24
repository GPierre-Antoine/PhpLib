<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 17:53
 */


use Psr\Autoload\AutoloaderClass;

$base = __DIR__ . "/lib";

// use phpunit with option :
// --bootstrap bootstrap.php

require_once $base . "/Psr/Autoload/AutoloaderClass.php";

$autoload = new AutoloaderClass();

$autoload->addNamespace('Psr', $base . "/Psr");
$autoload->addNamespace('PAG', $base . "/PAG");
$autoload->addNamespace('Experimental', $base . "/Experimental");

$autoload->register();

printf("Bootstrap OK\n");