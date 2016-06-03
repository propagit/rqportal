<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

include __DIR__ . "/../vendor/autoload.php";

# Use Loader() to autoload our model
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    __DIR__ . '/../app/models/',
    __DIR__ . '/../app/library/',
    __DIR__ . '/../app/views/'
))->register();

$di = new \Phalcon\DI\FactoryDefault();

$config = include __DIR__ . '/../app/config/config.php';

$di->set('config', $config);

# Set up the database service
$di->set('db', function() use($config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

# Set up the queue service
$di->set('queue', function() use($config) {
    return new \Phalcon\Queue\Beanstalk(array(
        'host' => $config->beanstalk->host
    ));
});

$di->set('view', function () use ($config) {

    $view = new \Phalcon\Mvc\View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => true
            ));

            $compiler = $volt->getCompiler();

            return $volt;
        }
    ));

    return $view;
}, true);

$di->set('mail', function(){
    return new Mail();
});

# Create and bind the DI to the application
$app = new \Phalcon\Mvc\Micro($di);


// define the routes here
# Add new supplier
$app->post('/supplier', function() use($app, $config){
    $request = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    // $response->setHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
    // $response->setHeader('Access-Control-Allow-Methods', 'POST,GET,OPTIONS,PUT,DELETE');

    # Check required fields for Supplier
    $required_fields = array(
        'name' => 'Contact Name',
        'business' => 'Business Name',
        'company' => 'Company Name',
        'abn_acn' => 'ABN/ACN',
        'address' => 'Address',
        'suburb' => 'Suburb',
        'state' => 'State',
        'postcode' => 'Postcode',
        'phone' => 'Phone',
        'email' => 'Email'
    );

    $errors = array();
    foreach($required_fields as $key => $label) {
        if (!isset($request->$key)) {
            $errors[] = $label . ' is required';
        }
    }


    if (count($errors) > 0) {
        $response->setStatusCode(400, "Bad request");
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
        return $response;
    }

    $phql = "INSERT INTO Supplier (status, name, business, company, abn_acn, address, suburb, state, postcode, phone, email, website, about, created_on) VALUES (:status:, :name:, :business:, :company:, :abn_acn:, :address:, :suburb:, :state:, :postcode:, :phone:, :email:, :website:, :about:, :created_on:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'status' => Supplier::APPLIED,
        'name' => $request->name,
        'business' => $request->business,
        'company' => $request->company,
        'abn_acn' => $request->abn_acn,
        'address' => $request->address,
        'suburb' => $request->suburb,
        'state' => $request->state,
        'postcode' => $request->postcode,
        'phone' => $request->phone,
        'email' => $request->email,
        'website' => isset($request->website) ? $request->website : '',
        'about' => isset($request->about) ? $request->about : '',
        'created_on' => new Phalcon\Db\RawValue('now()')
    ));

    if ($status->success() == true) {
        $app->mail->send(
            array('sales@removalistquote.com.au' => 'Team'), # hard code for now
            'New Member Sign Up',
            'new_applicant',
            array(
                'name' => $request->name,
                'business' => $request->business,
                'company' => $request->company,
                'abn_acn' => $request->abn_acn,
                'address' => $request->address,
                'suburb' => $request->suburb,
                'state' => $request->state,
                'postcode' => $request->postcode,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'about' => $request->about
            )
        );

        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK'));
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
    }

    return $response;

});

# Get the postcode
$app->get('/postcode/{keyword}', function($keyword) use($app) {
    if (strlen($keyword) < 3) { return; }
    $phql = "SELECT * FROM Postcodes WHERE postcode LIKE :keyword: OR suburb LIKE :keyword:";
    $postcodes = $app->modelsManager->executeQuery($phql, array(
        'keyword' => '%' . urldecode($keyword) . '%'
    ));

    $data = array();
    foreach($postcodes as $postcode) {
        $name = ucwords(strtolower($postcode->suburb)) . ', ' . strtoupper($postcode->state) . ' ' . $postcode->postcode;
        $data[] = array(
            'postcode' => $postcode->postcode,
            'suburb' => $postcode->suburb,
            'name' => $name
        );
    }
    #echo json_encode(array('postcodes' => $data));

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    $response->setStatusCode(201, "Created");
    $response->setJsonContent(array('postcodes' => $data));
    return $response;
});

# Add new removal quote
$app->post('/quote/removal', function() use($app, $config) {
    $quote = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    # Check required fields for Removal
    $required_fields = array(
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'customer_phone' => 'Customer Phone',
        'moving_from' => 'Moving From',
        'moving_to' => 'Moving To',
        'moving_date' => 'Moving Date',
        'bedrooms' => 'Bedrooms',
        'packing' => 'Packing Needs'
    );

    $errors = array();
    foreach($required_fields as $key => $label) {
        if (!isset($quote->$key)) {
            $errors[] = $label . ' is required';
        }
    }


    if (count($errors) > 0) {
        $response->setStatusCode(400, "Bad request");
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
        return $response;
    }


    # Get from postcode
    $from = $quote->moving_from->originalObject;
    $phql = "SELECT * FROM Postcodes WHERE postcode = :postcode: AND suburb = :suburb:";
    $from_postcode = $app->modelsManager->executeQuery($phql, array(
        'postcode' => $from->postcode,
        'suburb' => $from->suburb
    ))->getFirst();

    # Get to postcode
    $to = $quote->moving_to->originalObject;
    $to_postcode = $app->modelsManager->executeQuery($phql, array(
        'postcode' => $to->postcode,
        'suburb' => $to->suburb
    ))->getFirst();


    # Check if this is duplicate quote
    $now = date('Y-m-d H:i:s');
    $last_24_hr = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($now)));
    #$phql = "SELECT * FROM Removal WHERE customer_email = :customer_email: AND from_postcode = :from_postcode: AND to_postcode = :to_postcode: AND (created_on <= :created_on_now: AND created_on >= :created_on_last24hr:)  ORDER BY id ASC";
    $phql = "SELECT * FROM Removal WHERE customer_email = :customer_email: AND from_postcode = :from_postcode: AND to_postcode = :to_postcode: AND created_on >= :created_on_last24hr: ORDER BY id ASC";
    $duplicate = $app->modelsManager->executeQuery($phql, array(
        'customer_email' => $quote->customer_email,
        'from_postcode' => strlen($from_postcode->postcode) < 4 ? '0' . $from_postcode->postcode : $from_postcode->postcode,
        'to_postcode' => strlen($to_postcode->postcode) < 4 ? '0' . $to_postcode->postcode : $to_postcode->postcode,
        #'created_on_now' => $now,
        'created_on_last24hr' => $last_24_hr
    ))->getFirst();

    $is_duplicate = 0;
    $parent_id = 0;
    if($duplicate){
        $is_duplicate = 1;
        $parent_id = $duplicate->id;
    }

    $phql = "INSERT INTO Removal (customer_name, customer_email, customer_phone, from_postcode, from_lat, from_lon, to_postcode, to_lat, to_lon, moving_date, moving_type, bedrooms, packing, notes, is_international,from_country,to_country, from_country_id, to_country_id, is_duplicate, parent_id, duplicate_status, created_on, auto_distributed) VALUES (:customer_name:, :customer_email:, :customer_phone:, :from_postcode:, :from_lat:, :from_lon:, :to_postcode:, :to_lat:, :to_lon:, :moving_date:, :moving_type:, :bedrooms:, :packing:, :notes:,:is_international:,:from_country:,:to_country:, :from_country_id:, :to_country_id:, :is_duplicate:, :parent_id:, :duplicate_status:, :created_on:, :auto_distributed:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'customer_name' => $quote->customer_name,
        'customer_email' => $quote->customer_email,
        'customer_phone' => $quote->customer_phone,
        'from_postcode' => strlen($from_postcode->postcode) < 4 ? '0' . $from_postcode->postcode : $from_postcode->postcode,
        'from_lat' => $from_postcode->lat,
        'from_lon' => $from_postcode->lon,
        'to_postcode' => strlen($to_postcode->postcode) < 4 ? '0' . $to_postcode->postcode : $to_postcode->postcode,
        'to_lat' => $to_postcode->lat,
        'to_lon' => $to_postcode->lon,
        'moving_date' => $quote->moving_date,
        'moving_type' => $quote->moving_type,
        'bedrooms' => $quote->bedrooms,
        'packing' => $quote->packing,
        'notes' => $quote->notes,
        'is_international' => 'no',
        'from_country' => '-',
        'to_country' => '-',
        'from_country_id' => 0,
        'to_country_id' => 0,
        'is_duplicate' => $is_duplicate,
        'parent_id' => $parent_id,
        'duplicate_status' => 0,
        'created_on' => new Phalcon\Db\RawValue('now()'),
        'auto_distributed' => 0
    ));

    if(!$is_duplicate){
        if ($status->success() == true) {
            $response->setStatusCode(201, "Created");
            $response->setJsonContent(array('status' => 'OK', 'data' => $job_id));
        } else {
            $response->setStatusCode(409, "Conflict");
            $errors = array();
            foreach($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
        }
    }else{
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK', 'data' => ''));
    }

    return $response;
});

# Add new storage quote
$app->post('/quote/storage', function() use($app, $config) {
    $quote = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');

    # Check required fields for Removal
    $required_fields = array(
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'customer_phone' => 'Customer Phone',
        'pickup' => 'Pick Up Location',
        'containers' => 'Containers',
        'period' => 'Period'
    );

    $errors = array();
    foreach($required_fields as $key => $label) {
        if (!isset($quote->$key)) {
            $errors[] = $label . ' is required';
        }
    }


    if (count($errors) > 0) {
        $response->setStatusCode(400, "Bad request");
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
        return $response;
    }

    # Get pickup postcode
    $pickup = $quote->pickup->originalObject;
    $phql = "SELECT * FROM Postcodes WHERE postcode = :postcode: AND suburb = :suburb:";
    $pickup_postcode = $app->modelsManager->executeQuery($phql, array(
        'postcode' => $pickup->postcode,
        'suburb' => $pickup->suburb
    ))->getFirst();

    $phql = "INSERT INTO Storage (customer_name, customer_email, customer_phone, pickup_postcode, pickup_lat, pickup_lon, containers, period, notes, created_on, auto_distributed) VALUES (:customer_name:, :customer_email:, :customer_phone:, :pickup_postcode:, :pickup_lat:, :pickup_lon:, :containers:, :period:, :notes:, :created_on:, :auto_distributed:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'customer_name' => $quote->customer_name,
        'customer_email' => $quote->customer_email,
        'customer_phone' => $quote->customer_phone,
        'pickup_postcode' => strlen($pickup_postcode->postcode) < 4 ? '0' . $pickup_postcode->postcode : $pickup_postcode->postcode,
        'pickup_lat' => $pickup_postcode->lat,
        'pickup_lon' => $pickup_postcode->lon,
        'containers' => $quote->containers,
        'period' => $quote->period,
        'notes' => $quote->notes,
        'created_on' => new Phalcon\Db\RawValue('now()'),
        'auto_distributed' => 0
    ));

    if ($status->success() == true) {
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK', 'data' => $job_id));
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
    }

    return $response;
});

# Get the countries
$app->get('/country/{keyword}', function($keyword) use($app) {
    if (strlen($keyword) < 3) { return; }
    $phql = "SELECT * FROM Countries WHERE name LIKE :keyword:";
    $countries = $app->modelsManager->executeQuery($phql, array(
        'keyword' => '%' . $keyword . '%'
    ));

    $data = array();
    foreach($countries as $country) {
        $name = ucwords(strtolower($country->name)) . ' [' . $country->abbr . ']';
        $data[] = array(
            'country_id' => $country->id,
            'name' => $name
        );
    }
    // echo json_encode(array('postcodes' => $data));

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    $response->setStatusCode(201, "Created");
    $response->setJsonContent(array('countries' => $data));
    return $response;
});


# Add new international removal quote
$app->post('/quote/international', function() use($app, $config) {
    $quote = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    # Check required fields for Removal
    $required_fields = array(
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'customer_phone' => 'Customer Phone',
        'moving_from' => 'Moving From',
        'moving_to' => 'Moving To',
        'moving_date' => 'Moving Date',
        'bedrooms' => 'Bedrooms',
        'packing' => 'Packing Needs'
    );

    $errors = array();
    foreach($required_fields as $key => $label) {
        if (!isset($quote->$key)) {
            $errors[] = $label . ' is required';
        }
    }


    if (count($errors) > 0) {
        $response->setStatusCode(400, "Bad request");
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
        return $response;
    }


    # Get from countries
    $from = $quote->moving_from->originalObject;
    $phql = "SELECT * FROM Countries WHERE id = :id:";
    $from_country = $app->modelsManager->executeQuery($phql, array(
        'id' => $from->country_id
    ))->getFirst();

    # Get to postcode
    $to = $quote->moving_to->originalObject;
    $to_country = $app->modelsManager->executeQuery($phql, array(
        'id' => $to->country_id
    ))->getFirst();


    $phql = "INSERT INTO Removal (customer_name, customer_email, customer_phone, from_postcode, from_lat, from_lon, to_postcode, to_lat, to_lon, moving_date, moving_type, bedrooms, packing, notes, is_international,from_country,to_country, from_country_id, to_country_id, is_duplicate, parent_id, duplicate_status, created_on, auto_distributed) VALUES (:customer_name:, :customer_email:, :customer_phone:, :from_postcode:, :from_lat:, :from_lon:, :to_postcode:, :to_lat:, :to_lon:, :moving_date:, :moving_type:, :bedrooms:, :packing:, :notes:,:is_international:,:from_country:,:to_country:, :from_country_id:, :to_country_id:, :is_duplicate:, :parent_id:, :duplicate_status:, :created_on:, :auto_distributed:)";
    $status = $app->modelsManager->executeQuery($phql, array(
        'customer_name' => $quote->customer_name,
        'customer_email' => $quote->customer_email,
        'customer_phone' => $quote->customer_phone,
        'from_postcode' => 'INTL',
        'from_lat' => $from_country->lat,
        'from_lon' => $from_country->lon,
        'to_postcode' => 'INTL',
        'to_lat' => $to_country->lat,
        'to_lon' => $to_country->lon,
        'moving_date' => $quote->moving_date,
        'moving_type' => $quote->moving_type,
        'bedrooms' => $quote->bedrooms,
        'packing' => $quote->packing,
        'notes' => $quote->notes,
        'is_international' => 'yes',
        'from_country' => $from_country->name,
        'to_country' => $to_country->name,
        'from_country_id' => $from_country->id,
        'to_country_id' => $to_country->id,
        'is_duplicate' => 0,
        'parent_id' => 0,
        'duplicate_status' => 0,
        'created_on' => new Phalcon\Db\RawValue('now()'),
        'auto_distributed' => 0
    ));

    if ($status->success() == true) {
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK', 'data' => $job_id));
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(array('status' => 'ERROR', 'message' => $errors));
    }

    return $response;
});


$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'Dude, you are not supposed to be here!';
});

$app->handle();

