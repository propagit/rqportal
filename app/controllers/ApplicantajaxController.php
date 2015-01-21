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
}

