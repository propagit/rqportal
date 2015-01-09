<?php

class Postcodes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $postcode;

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
    public $dc;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $lat;

    /**
     *
     * @var string
     */
    public $lon;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'postcode' => 'postcode', 
            'suburb' => 'suburb', 
            'state' => 'state', 
            'dc' => 'dc', 
            'type' => 'type', 
            'lat' => 'lat', 
            'lon' => 'lon'
        );
    }

}
