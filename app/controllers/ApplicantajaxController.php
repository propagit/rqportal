<?php

class ApplicantajaxController extends ControllerAjax
{
    public function allLocalAction()
    {
        $zones = ZoneLocal::find("user_id = " . $this->user->id);
        $results = array();
        foreach($zones as $zone) {
            $results[] = $zone->toArray();
        }
        $this->view->zones = $results;
    }

    public function addLocalAction()
    {
        $request = $this->request->getJsonRawBody();
        $zone = new ZoneLocal();
        $zone->user_id = $this->user->id;
        $zone->postcode = $request->postcode;
        $zone->latitude = $request->latitude;
        $zone->longitude = $request->longitude;
        $zone->distance = $request->distance;

        if ($zone->save())
        {
            if ($this->user->status == User::APPROVED)
            {
                # Add to the Queue
                $job_id = $this->queue->put(array('local' => $zone->id));
            }

            $this->view->zone = $zone->toArray();
            $this->response->setStatusCode(200, 'OK');
        }
        else
        {
            $this->response->setStatusCode(400, 'ERROR');
            $errors = array();
            foreach($zone->getMessages() as $message) {
                $errors[] = (string) $message;
            }
            $this->view->errors = $errors;
        }
    }

    public function deleteLocalAction($id)
    {
        # Find country zone associated and delete country zones first
        $zone_country = ZoneCountry::find("local_id = $id");
        foreach($zone_country as $z) {
            $z->delete();
        }

        $zone = ZoneLocal::findFirst("id = $id AND user_id = " . $this->user->id);
        if ($zone) {
            $zone->delete();
        }
    }

    public function allCountryAction()
    {
        $zones = ZoneCountry::find("user_id = " . $this->user->id);
        $results = array();
        foreach($zones as $zone) {
            $results[] = $zone->toArray();
        }
        $this->view->zones = $results;
    }


    public function addCountryAction()
    {
        $request = $this->request->getJsonRawBody();
        $zone_local = ZoneLocal::findFirst("id = '" . $request->local_id . "'");
        $zone_country = new ZoneCountry();
        $zone_country->user_id = $this->user->id;
        $zone_country->local_id = $zone_local->id;
        $zone_country->distance = $request->distance;
        if ($zone_country->save())
        {
            if ($this->user->status == User::APPROVED)
            {
                # Add to the Queue
                $job_id = $this->queue->put(array('country' => $zone_country->id));
            }
            $this->view->zone = $zone_country->toArray();
            $this->response->setStatusCode(200, 'OK');
        }
        else
        {
            $this->response->setStatusCode(400, 'ERROR');
            $errors = array();
            foreach($zone_country->getMessages() as $message) {
                $errors[] = (string) $message;
            }
            $this->view->errors = $errors;
        }
    }

    public function deleteCountryAction($id)
    {
        $zone = ZoneCountry::findFirst("id = $id AND user_id = " . $this->user->id);
        if ($zone) {
            $zone->delete();
        }
    }


    public function allInterstateAction()
    {
        $zones = ZoneInterstate::find("user_id = " . $this->user->id);
        $results = array();
        foreach($zones as $zone) {
            $results[] = $zone->toArray();
        }
        $this->view->zones = $results;
    }

    public function addInterstateAction()
    {
        $request = $this->request->getJsonRawBody();
        $zone = new ZoneInterstate();
        $zone->user_id = $this->user->id;
        $zone->postcode1 = $request->postcode1->originalObject->postcode;
        $zone->latitude1 = $request->latitude1;
        $zone->longitude1 = $request->longitude1;
        $zone->distance1 = $request->distance1;

        $zone->postcode2 = $request->postcode2->originalObject->postcode;
        $zone->latitude2 = $request->latitude2;
        $zone->longitude2 = $request->longitude2;
        $zone->distance2 = $request->distance2;

        if ($zone->save()) {

            if ($this->user->status == User::APPROVED)
            {
                # Add to the Queue
                $job_id = $this->queue->put(array('country' => $zone->id));
            }
            $this->response->setStatusCode(200, 'OK');
            $this->view->zone = $zone->toArray();
        }
        else
        {
            $this->response->setStatusCode(400, 'ERROR');
            $errors = array();
            foreach($zone->getMessages() as $message) {
                $errors[] = (string) $message;
            }
            $this->view->errors = $errors;
        }
    }



    public function deleteInterstateAction($id)
    {
        $zone = ZoneInterstate::findFirst("id = $id AND user_id = " . $this->user->id);
        if ($zone) {
            $zone->delete();
        }
    }

    public function paymentAction()
    {
        $request = $this->request->getJsonRawBody();

        if (!isset($request->title) || !isset($request->firstname) || !isset($request->lastname)
            || !isset($request->ccnumber) || !isset($request->ccexpmonth)
            || !isset($request->ccexpyear) || !isset($request->cvn) || !isset($request->agree)) {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->error = 'Invalid request';
            return;
        }

        $supplier = Supplier::findFirstByUserId($this->user->id);
        if (!$supplier) {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->error = 'Supplier not found';
            return;
        }

        $eway_customer = array(
            'CustomerRef' => $supplier->user_id,
            'Title' => $request->title,
            'FirstName' => $request->firstname,
            'LastName' => $request->lastname,
            'Email' => $supplier->email,
            'Address' => $supplier->address,
            'Suburb' => $supplier->suburb,
            'State' => $supplier->state,
            'PostCode' => $supplier->postcode,
            'Phone' => $supplier->phone,
            'Mobile' => '',
            'Fax' => '',
            'Country' => 'au',
            'Company' => $supplier->company,
            'JobDesc' => '',
            'URL' => $supplier->website,
            'Comments' => $supplier->about,
            'CCNameOnCard' => $request->firstname . ' ' . $request->lastname,
            'CCNumber' => $request->ccnumber,
            'CCExpiryMonth' => $request->ccexpmonth,
            'CCExpiryYear' => $request->ccexpyear
        );

        # Create customer
        try {
            $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
            $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
            $client->__setSoapHeaders(array($header));
            $result = $client->CreateCustomer($eway_customer);
            $supplier->eway_customer_id = $result->CreateCustomerResult;
            $supplier->cvn = $request->cvn;
            $supplier->status = Supplier::APPROVED;
            $supplier->save();

            # Try process a small amount payment

            # Add to the Queue
            $job_id = $this->queue->put(array('location' => $this->user->id));

            $this->user->status = User::APPROVED;
            $this->user->save();
            $this->session->set('auth', array(
                'id' => $this->user->id,
                'username' => $this->user->username,
                'status' => $this->user->status,
                'level' => $this->user->level
            ));
            $this->response->setStatusCode(200, 'OK');
        } catch(Exception $e) {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->error = $e->getMessage();
        }
    }
}

