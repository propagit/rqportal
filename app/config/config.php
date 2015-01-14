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
        'baseUri'        => '/rqportal/',
        'publicUrl'      => 'http://localhost/rqportal/'
    ),
    'mail' => array(
        'toName'    => 'Team',
        'toEmail'   => 'team@propagate.com.au',
        'fromName'  => 'Removalist Quote',
        'fromEmail' => 'propagate.au@gmail.com',
        'smtp'      => array(
            'server'    => 'smtp.gmail.com',
            'port'      => 587,
            'security'  => 'tls',
            'username'  => 'propagate.au@gmail.com',
            'password'  => 'm0r3m0n3Y'
        )
    ),
    'supplierPerQuote' => 3
));
