<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Queue\Beanstalk;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Register the global configuration as config
 */
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * We register the events manager
 */
$di->set('dispatcher', function() use ($di) {

    // $eventsManager = new EventsManager;
    $eventsManager = $di->getShared('eventsManager');

    // Custom ACL Class
    $security = new SecurityPlugin($di);

    // $eventsManager->attach('dispatch', $security);
    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    // $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
    // return xdebug_print_function_stack("stop here!");

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    // $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => true
            ));

            $compiler = $volt->getCompiler();
            $compiler->addFunction('is_a', 'is_a');

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * Queue to distribute quotes in real-time
 */
$di->set('queue', function() use($config) {
    if (isset($config->beanstalk->disabled) && $config->beanstalk->disabled) {
        return new DummyServer();
    }

    if (!isset($config->beanstalk->host)) {
        throw new \Exception("Beanstalk is not configured");
    }

    return new Beanstalk(array(
        'host' => $config->beanstalk->host,
        #'port' => $config->beanstalk->port
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
    return new FlashSession(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
    ));
});

/**
 * Register a user component
 */
$di->set('elements', function(){
    return new Elements();
});

/**
 * Register Mail service
 */
$di->set('mail', function(){
    return new Mail();
});
