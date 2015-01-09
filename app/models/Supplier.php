<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Supplier extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $status;

    const APPLIED = 0;
    const ACTIVATED = 1;
    const APPROVED = 2;

    /**
     *
     * @var string
     */
    public $key;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $business;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $suburb;

    /**
     *
     * @var integer
     */
    public $state;

    /**
     *
     * @var string
     */
    public $postcode;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $website;

    /**
     *
     * @var string
     */
    public $about;

    /**
     *
     * @var string
     */
    public $created_on;

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'status' => 'status',
            'key' => 'key',
            'name' => 'name',
            'business' => 'business',
            'address' => 'address',
            'suburb' => 'suburb',
            'state' => 'state',
            'postcode' => 'postcode',
            'phone' => 'phone',
            'email' => 'email',
            'website' => 'website',
            'about' => 'about',
            'created_on' => 'created_on'
        );
    }

    public function afterCreate() {
        $this->getDI()->getMail()->send(
            array($this->email => $this->name),
            'Please confirm your email',
            'confirmation',
            array('confirmUrl' => '/confirm/')
        );
    }

}
