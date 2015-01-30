<?php

/**
 * Command Line Interface (CLI) Bootstrap
 */

error_reporting(E_ALL);
set_time_limit(0);

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

define('APP_PATH', realpath('..'));

/**
 * Read the configuration
 */
$config = include __DIR__ . "/../app/config/config.php";

/**
 * Read auto-loader
 */
include __DIR__ . "/../app/config/loader.php";

/**
 * Include composer autoloader
 */
include __DIR__ . "/../vendor/autoload.php";

/**
 * Read services
 */
include __DIR__ . "/../app/config/services.php";

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
#$di = new \Phalcon\DI\FactoryDefault();

/**
 * Include composer autoloader
 */
#require APP_PATH . "/vendor/autoload.php";
