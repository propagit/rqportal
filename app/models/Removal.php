<?php

class Removal extends \Phalcon\Mvc\Model
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
    public $from_postcode;

    /**
     *
     * @var string
     */
    public $from_lat;

    /**
     *
     * @var string
     */
    public $from_lon;

    /**
     *
     * @var string
     */
    public $to_postcode;

    /**
     *
     * @var string
     */
    public $to_lat;

    /**
     *
     * @var string
     */
    public $to_lon;

    /**
     *
     * @var string
     */
    public $moving_date;

    /**
     *
     * @var string
     */
    public $moving_type;

    /**
     *
     * @var string
     */
    public $bedrooms;

    /**
     *
     * @var string
     */
    public $packing;

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
            'from_postcode' => 'from_postcode',
            'from_lat' => 'from_lat',
            'from_lon' => 'from_lon',
            'to_postcode' => 'to_postcode',
            'to_lat' => 'to_lat',
            'to_lon' => 'to_lon',
            'moving_date' => 'moving_date',
            'moving_type' => 'moving_type',
            'bedrooms' => 'bedrooms',
            'packing' => 'packing',
            'notes' => 'notes',
            'created_on' => 'created_on'
        );
    }

}
