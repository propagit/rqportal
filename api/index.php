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

# Set up the queue service
$di->set('queue', function() use($config) {
    return new \Phalcon\Queue\Beanstalk(array(
        'host' => $config->beanstalk->host
    ));
});

# Create and bind the DI to the application
$app = new \Phalcon\Mvc\Micro($di);

// define the routes here

# Add new supplier
$app->post('/supplier', function() use($app, $config){
    $request = $app->request->getJsonRawBody();

    $response = new Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
    // $response->setHeader('Access-Control-Allow-Headers', '*');
    // $response->setHeader('Access-Control-Allow-Methods', '*');

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
        # Add to queue
        $job_id = $app->queue->put(array('removal' => $status->getModel()->id));

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
        # Add to queue
        $job_id = $app->queue->put(array('storage' => $status->getModel()->id));

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

$app->handle();

