<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

# Use Loader() to autoload our model
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    __DIR__ . '/../app/models/'
))->register();

$di = new \Phalcon\DI\FactoryDefault();

$config = include __DIR__ . '/../app/config/config.php';

# Set up the database service
$di->set('db', function() use($config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

# Create and bind the DI to the application
$app = new \Phalcon\Mvc\Micro($di);

// define the routes here
# Get the postcode
$app->get('/postcode/{keyword}', function($keyword) use($app) {
    $phql = "SELECT * FROM Postcodes WHERE postcode LIKE :keyword: OR suburb LIKE :keyword:";
    $postcodes = $app->modelsManager->executeQuery($phql, array(
        'keyword' => '%' . $keyword . '%'
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
    // echo json_encode(array('postcodes' => $data));

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


    $phql = "INSERT INTO Removal (customer_name, customer_email, customer_phone, from_postcode, from_lat, from_lon, to_postcode, to_lat, to_lon, moving_date, moving_type, bedrooms, packing, notes, created_on) VALUES (:customer_name:, :customer_email:, :customer_phone:, :from_postcode:, :from_lat:, :from_lon:, :to_postcode:, :to_lat:, :to_lon:, :moving_date:, :moving_type:, :bedrooms:, :packing:, :notes:, :created_on:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'customer_name' => $quote->customer_name,
        'customer_email' => $quote->customer_email,
        'customer_phone' => $quote->customer_phone,
        'from_postcode' => $from_postcode->postcode,
        'from_lat' => $from_postcode->lat,
        'from_lon' => $from_postcode->lon,
        'to_postcode' => $to_postcode->postcode,
        'to_lat' => $to_postcode->lat,
        'to_lon' => $to_postcode->lon,
        'moving_date' => $quote->moving_date,
        'moving_type' => $quote->moving_type,
        'bedrooms' => $quote->bedrooms,
        'packing' => $quote->packing,
        'notes' => $quote->notes,
        'created_on' => new Phalcon\Db\RawValue('now()')
    ));

    if ($status->success() == true) {

        $url = $config->application->publicUrl . 'distribute/removal/' . $status->getModel()->id;;
        exec("curl $url > /dev/null 2>&1 &");
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK', 'data' => $quote));
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

# Add new storage quote
$app->post('/quote/storage', function() use($app, $config) {
    $quote = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();

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

    $phql = "INSERT INTO Storage (customer_name, customer_email, customer_phone, pickup_postcode, pickup_lat, pickup_lon, containers, period, notes, created_on) VALUES (:customer_name:, :customer_email:, :customer_phone:, :pickup_postcode:, :pickup_lat:, :pickup_lon:, :containers:, :period:, :notes:, :created_on:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'customer_name' => $quote->customer_name,
        'customer_email' => $quote->customer_email,
        'customer_phone' => $quote->customer_phone,
        'pickup_postcode' => $pickup_postcode->postcode,
        'pickup_lat' => $pickup_postcode->lat,
        'pickup_lon' => $pickup_postcode->lon,
        'containers' => $quote->containers,
        'period' => $quote->period,
        'notes' => $quote->notes,
        'created_on' => new Phalcon\Db\RawValue('now()')
    ));

    if ($status->success() == true) {
        $url = $config->application->publicUrl . 'distribute/storage/' . $status->getModel()->id;;
        exec("curl $url > /dev/null 2>&1 &");
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array('status' => 'OK', 'data' => $quote));
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

$app->handle();

