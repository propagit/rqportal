<?php

class ZoneInterstate extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $postcode1;

    /**
     *
     * @var double
     */
    public $latitude1;

    /**
     *
     * @var double
     */
    public $longitude1;

    /**
     *
     * @var integer
     */
    public $distance1;

    /**
     *
     * @var string
     */
    public $postcode2;

    /**
     *
     * @var double
     */
    public $latitude2;

    /**
     *
     * @var double
     */
    public $longitude2;

    /**
     *
     * @var integer
     */
    public $distance2;

    /**
     *
     * @var string
     */
    public $pool1;

    /**
     *
     * @var string
     */
    public $pool2;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'postcode1' => 'postcode1',
            'latitude1' => 'latitude1',
            'longitude1' => 'longitude1',
            'distance1' => 'distance1',
            'postcode2' => 'postcode2',
            'latitude2' => 'latitude2',
            'longitude2' => 'longitude2',
            'distance2' => 'distance2',
            'pool1' => 'pool1',
            'pool2' => 'pool2'
        );
    }

    public function toJson()
    {
        return array(
            'id' => $this->id,
            'postcode1' => $this->postcode1,
            'distance1' => $this->distance1,
            'postcode2' => $this->postcode2,
            'distance2' => $this->distance2
        );
    }

    public function drawCircle1()
    {
        return array(
            'id' => $this->id,
            'center' => array(
                'latitude' => $this->latitude1,
                'longitude' => $this->longitude1
            ),
            'radius' => $this->distance1 * 1000,
            'stroke' => array(
                'color' => '#08B21F',
                'weight' => 0,
                'opacity' => 1
            ),
            'fill' => array(
                'color' => '#9e0a20',
                'opacity' => 0.3
            ),
            'geodesic' => true
        );
    }

    public function drawCircle2()
    {
        return array(
            'id' => $this->id,
            'center' => array(
                'latitude' => $this->latitude2,
                'longitude' => $this->longitude2
            ),
            'radius' => $this->distance2 * 1000,
            'stroke' => array(
                'color' => '#08B21F',
                'weight' => 0,
                'opacity' => 1
            ),
            'fill' => array(
                'color' => '#9e0a20',
                'opacity' => 0.3
            ),
            'geodesic' => true
        );
    }

    public function drawPath()
    {
        return array(
            'id' => $this->id,
            'path' => array(
                array('latitude' => $this->latitude1, 'longitude' => $this->longitude1),
                array('latitude' => $this->latitude2, 'longitude' => $this->longitude2)
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

    public function generatePool()
    {
        # Pool 1
        $query = new Phalcon\Mvc\Model\Query("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode1, p.postcode) <= $this->distance1", $this->getDI());
        $postcodes = $query->execute();
        $pool1 = array();
        foreach($postcodes as $p) {
            $pool1[] = $p->postcode;
        }
        $this->pool1 = json_encode($pool1);

        # Pool 2
        $query = new Phalcon\Mvc\Model\Query("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode2, p.postcode) <= $this->distance2", $this->getDI());
        $postcodes = $query->execute();
        $pool2 = array();
        foreach($postcodes as $p) {
            $pool2[] = $p->postcode;
        }
        $this->pool2 = json_encode($pool2);

        $this->save();
        return count($pool1) + count($pool2);
    }

}
