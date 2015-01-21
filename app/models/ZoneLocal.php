<?php

class ZoneLocal extends BaseModel
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
    public $postcode;

    /**
     *
     * @var double
     */
    public $latitude;

    /**
     *
     * @var double
     */
    public $longitude;

    /**
     *
     * @var integer
     */
    public $distance;

    /**
     *
     * @var string
     */
    public $pool;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'postcode' => 'postcode',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'distance' => 'distance',
            'pool' => 'pool'
        );
    }

    public function toArray($columns = NULL)
    {
        $zone = parent::toArray();
        $zone['circle'] = $this->drawCircle();
        $zone['marker'] = $this->drawMarker();
        return $zone;
    }

    public function drawCircle()
    {
        return array(
            'id' => $this->id,
            'center' => array(
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ),
            'radius' => $this->distance * 1000,
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

    public function drawMarker()
    {
        return array(
            'id' => $this->id,
            'coords' => array(
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            )
        );
    }

    public function generatePool()
    {
        $result = $this->db->query("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode, p.postcode) <= $this->distance");

        $pool = array();
        $result->setFetchMode(Phalcon\Db::FETCH_OBJ);
        while($postcode = $result->fetch()) {
            $pool[] = $postcode->postcode;
        }
        $this->pool = json_encode($pool);
        $this->save();
        return count($pool);
    }
}
