<?php

error_reporting(E_ALL);

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

date_default_timezone_set('Australia/Melbourne');

// $debug = new \Phalcon\Debug();
// $debug->listen();

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
