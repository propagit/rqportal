<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->forward('dashboard');
    }

    public function generateAction()
    {
        $zones = ZoneCountry::find();
        foreach($zones as $zone) {
            $zone->generatePool();
        }

        $zones = ZoneInterstate::find();
        foreach($zones as $zone) {
            $zone->generatePool();
        }
    }
}

