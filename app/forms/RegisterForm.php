<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;


class RegisterForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        // Username
        $username = new Text('username');
        $username->setLabel('Username');
        $username->setFilters(array('alpha'));
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your desired username'
            ))
        ));
        $this->add($username);

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            ))
        ));
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel('Repeat Password');
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Confirmation password is required'
            ))
        ));
        $this->add($repeatPassword);
    }
}
