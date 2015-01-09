<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;


class ZoneLocalForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        // Username
        $center = new Text('center');
        $center->setLabel('Enter your local zone post code');
        $center->setFilters(array('alpha'));
        $center->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your local post code'
            ))
        ));
        $this->add($center);

        # Radius / Distance
        $center = new Text('center');
        $center->setLabel('Enter your local zone post code');
        $center->setFilters(array('alpha'));
        $center->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your local post code'
            ))
        ));
        $this->add($center);
    }
}
