<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->forward('dashboard');
    }

    public function generateAction()
    {
        $local = ZoneLocal::find();
        foreach($local as $zone) {
            $zone->generatePool();
        }
    }
}

