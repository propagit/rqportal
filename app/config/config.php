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
        'publicUrl'      => 'http://nam.dev/rqportal/'
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
    'supplierPerQuote' => 3,
    'beanstalk' => array(
        'disabled'  => false,
        'host'      => '127.0.0.1',
        'port'      => '11300'
    ),

    'eway' => array(
        'endpoint' => 'https://www.eway.com.au/gateway/ManagedPaymentService/test/managedCreditCardPayment.asmx?WSDL',
        'headers' => array(
            'eWAYCustomerID' => '91893390',
            'Username' => 'nam@propagate.com.au.sand',
            'Password' => 'Q1w2e3r4t5'
        ),
        'namespace' => 'https://www.eway.com.au/gateway/managedpayment'

    )
));
