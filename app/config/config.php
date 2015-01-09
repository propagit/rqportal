<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'rqportal',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'formsDir'       => __DIR__ . '/../../app/forms/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => 'http://localhost/rqportal/',
    ),
    'mail' => array(
        'fromName'  => 'Removalist Quote',
        'fromEmail' => 'propagate.au@gmail.com',
        'smtp'      => array(
            'server'    => 'smtp.gmail.com',
            'port'      => 465,
            'security'  => 'ssl',
            'username'  => 'propagate.au@gmail.com',
            'password'  => 'm0r3m0n3Y'
        )
    )
));
