<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->forward('dashboard');
    }

    public function testAction()
    {
        /*$zone = ZoneLocal::findFirst(317);
        $pool = $zone->generatePool();
        print_r($pool); die();*/

        $removal = Removal::findFirst(21061);
        $from = Postcodes::findFirstByPostcode($removal->from_postcode);
        $to = Postcodes::findFirstByPostcode($removal->to_postcode);
        $cc = array_map('trim', explode(',', 'namnd86@gmail.com, daniel@propagate.com.au,namndvn@yahoo.com.au'));

        $this->mail->send(
            array("nam@propagate.com.au" => "Nam Nguyen"),
            'New Removalist Job',
            'new_removal',
            array(
                'removal' => $removal,
                'from' => $from,
                'to' => $to
            ),
            $cc
        );
    }
}

