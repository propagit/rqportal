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
    public $activation_key;

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
    public $company;

    /**
     *
     * @var string
     */
    public $abn_acn;

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
     * @var string
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
            'activation_key' => 'activation_key',
            'name' => 'name',
            'business' => 'business',
            'company' => 'company',
            'abn_acn' => 'abn_acn',
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
            array('team@propagate.com.au' => 'Team'), # hard code for now
            'New Member Sign Up',
            'new_applicant',
            array(
                'name' => $this->name,
                'business' => $this->business,
                'company' => $this->company,
                'abn_acn' => $this->abn_acn,
                'address' => $this->address,
                'suburb' => $this->suburb,
                'state' => $this->state,
                'postcode' => $this->postcode,
                'phone' => $this->phone,
                'email' => $this->email,
                'website' => $this->website,
                'about' => $this->about
            )
        );
    }
}
