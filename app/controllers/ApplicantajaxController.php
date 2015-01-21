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

}

