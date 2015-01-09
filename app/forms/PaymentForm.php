<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;

class PaymentForm extends Form
{

    /**
    *   Initialize the payment details form
    */
    public function initialize($entity = null, $options = array())
    {

        $name = new Text("cardname");
        $name->setLabel("Card Name");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Card Name is required'
            ))
        ));
        $this->add($name);

        $number = new Text("cardnumber");
        $number->setLabel("Card Number");
        $number->setFilters(array('float'));
        $number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Card Number is required'
            ))
        ));
        $this->add($number);

        $exp_month = new Select('exp_month', $this->elements->getMonths(), array(
            'useEmpty'  => true,
            'emptyText' => 'Month',
            'emptyValue' => ''
        ));
        $exp_month->setLabel("Expiry Month");
        $this->add($exp_month);



        $number = new Text("cvv");
        $number->setLabel("CVV Number");
        $number->setFilters(array('float'));
        $number->addValidators(array(
            new PresenceOf(array(
                'message' => 'CVV Number is required'
            ))
        ));
        $this->add($number);
    }
}
