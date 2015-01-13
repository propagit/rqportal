<?php

class Quote extends \Phalcon\Mvc\Model
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


    public function toJson()
    {
        $quote = array();
        foreach($this->columnMap() as $key => $value) {
            $quote[$key] = $this->$key;
        }
        $quote['path'] = $this->drawPath();
        $quote['from_marker'] = $this->drawFromMarker();
        $quote['to_marker'] = $this->drawToMarker();
        return $quote;
    }

    public function drawFromMarker()
    {
        return array(
            'id' => 1,
            'coords' => array(
                'latitude' => $this->from_lat,
                'longitude' => $this->from_lon,
            ),
            'options' => array(
                'draggable' => false
            )
        );
    }

    public function drawToMarker()
    {
        return array(
            'id' => 2,
            'coords' => array(
                'latitude' => $this->to_lat,
                'longitude' => $this->to_lon,
            ),
            'options' => array(
                'draggable' => false
            ),
            'events' => array(
            )
        );
    }

    public function drawPath()
    {
        return array(
            'id' => $this->id,
            'path' => array(
                array('latitude' => $this->from_lat, 'longitude' => $this->from_lon),
                array('latitude' => $this->to_lat, 'longitude' => $this->to_lon)
            ),
            'stroke' => array(
                'color' => '#08B21F',
                'weight' => 3
            ),
            'editable' => false,
            'draggable' => false,
            'geodesic' => true,
            'visible' => true,
            'icons' => array(
                array(
                    'icon' => array(
                        'path' => ''
                    ),
                    'offset' => '25px',
                    'repeat' => '50px'
                )
            )
        );
    }

}