<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class ProfileForm extends Form
{

    /**
    *   Initialize the company profile information form
    */
    public function initialize($entity = null, $options = array())
    {
        if ($options['edit']) {
            $this->add(new Hidden('id'));
        } else {
            #$this->add(new Text('id'));
        }

        $name = new Text("name");
        $name->setLabel("Contact Name *");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Contact Name is required'
            ))
        ));
        $this->add($name);

        $business = new Text("business");
        $business->setLabel("Business Name *");
        $business->setFilters(array('striptags', 'string'));
        $business->addValidators(array(
            new PresenceOf(array(
                'message' => 'Business Name is required'
            ))
        ));
        $this->add($business);

        $address = new Text("address");
        $address->setLabel("Address *");
        $address->setFilters(array('striptags', 'string'));
        $address->addValidators(array(
            new PresenceOf(array(
                'message' => 'Address is required'
            ))
        ));
        $this->add($address);

        $suburb = new Text("suburb");
        $suburb->setLabel("Suburb *");
        $suburb->setFilters(array('striptags', 'string'));
        $suburb->addValidators(array(
            new PresenceOf(array(
                'message' => 'City is required'
            ))
        ));
        $this->add($suburb);

        $state = new Select('state', State::find(), array(
            'using' => array('id', 'name'),
            'useEmpty'  => true,
            'emptyText' => 'Select State',
            'emptyValue' => ''
        ));
        $state->setLabel("State");
        $this->add($state);

        $postcode = new Text("postcode");
        $postcode->setLabel("Postcode *");
        $postcode->setFilters(array('float'));
        $postcode->addValidators(array(
            new PresenceOf(array(
                'message' => 'Postcode is required'
            ))
        ));
        $this->add($postcode);

        $phone = new Text("phone");
        $phone->setLabel("Phone *");
        $phone->setFilters(array('striptags', 'string'));
        $phone->addValidators(array(
            new PresenceOf(array(
                'message' => 'Phone is required'
            ))
        ));
        $this->add($phone);

        $email = new Text('email');
        $email->setLabel('Email *');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'Email is required'
            )),
            new Email(array(
                'message' => 'Email is not valid'
            ))
        ));
        $this->add($email);

        $website = new Text('website');
        $website->setLabel('Website');
        $website->setFilters(array('striptags', 'string'));
        $this->add($website);

        $about = new TextArea('about');
        $about->setLabel('About My Business');
        $about->setFilters(array('striptags', 'string'));
        $this->add($about);
    }
}
