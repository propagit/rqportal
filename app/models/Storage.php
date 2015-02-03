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
    public $pickup_lat;

    /**
     *
     * @var string
     */
    public $pickup_lon;

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
            'pickup_lat' => 'pickup_lat',
            'pickup_lon' => 'pickup_lon',
            'containers' => 'containers',
            'period' => 'period',
            'notes' => 'notes',
            'created_on' => 'created_on'
        );
    }

    public function toArray($columns = NULL)
    {
        $storage = parent::toArray();
        $storage['pickup_marker'] = $this->drawPickupMarker();
        return $storage;
    }

    public function drawPickupMarker()
    {
        return array(
            'id' => 1,
            'coords' => array(
                'latitude' => $this->pickup_lat,
                'longitude' => $this->pickup_lon,
            ),
            'options' => array(
                'draggable' => false
            )
        );
    }

    public function beforeDelete()
    {
        $conditions = "job_type = '" . Quote::STORAGE . "' AND job_id = " . $this->id;
        foreach(Quote::find($conditions) as $quote)
        {
            $quote->delete();
        }
        return true;
    }

}
