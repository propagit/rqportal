<?php

class Storage extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $customer_name;

    /**
     *
     * @var string
     */
    public $customer_email;

    /**
     *
     * @var string
     */
    public $customer_phone;

    /**
     *
     * @var string
     */
    public $pickup_postcode;

    /**
     *
     * @var string
     */
    public $containers;

    /**
     *
     * @var string
     */
    public $period;

    /**
     *
     * @var string
     */
    public $notes;

    /**
     *
     * @var string
     */
    public $created_on;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'customer_name' => 'customer_name', 
            'customer_email' => 'customer_email', 
            'customer_phone' => 'customer_phone', 
            'pickup_postcode' => 'pickup_postcode', 
            'containers' => 'containers', 
            'period' => 'period', 
            'notes' => 'notes', 
            'created_on' => 'created_on'
        );
    }

}
