<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->forward('dashboard');
    }

    public function testAction()
    {
        $zone = ZoneLocal::findFirst(317);
        $pool = $zone->generatePool();
        print_r($pool); die();
    }
}

