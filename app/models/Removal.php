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

    const ANY = 'any';
	const TWO_PLUS = '2+';
    const UNDER_THREE = '<3';
    const THREE_PLUS = '3+';
	
	
	/**
     *
     * @var string
     */
    public $is_international;

    const INT_YES = 'yes';
	const INT_NO = 'no';


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

    public function toArray($columns = NULL)
    {
        $removal = parent::toArray();
        // $removal['moving_date'] = strtotime($removal['moving_date']);
        $removal['from_marker'] = $this->drawFromMarker();
        $removal['to_marker'] = $this->drawToMarker();
        $removal['path'] = $this->drawPath();
        return $removal;
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

    public static function listBedsOptions()
    {
       /* return array(
            Removal::ANY => 'Any',
            Removal::UNDER_THREE => 'Under 3 Bedrooms',
            Removal::THREE_PLUS => '3+ Bedrooms'
        );*/
		 return array(
            Removal::ANY => 'Any',
            Removal::TWO_PLUS => '2+ Bedrooms'
        );
    }
	
	public static function listInternationalOptions()
	{
		return array(
            Removal::INT_NO => 'No',
            Removal::INT_YES => 'Yes'
        );
	}

    public function beforeDelete()
    {
        $conditions = "job_type = '" . Quote::REMOVAL . "' AND job_id = " . $this->id;
        foreach(Quote::find($conditions) as $quote)
        {
            $quote->delete();
        }
        return true;
    }
}
