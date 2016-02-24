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
        $phql = "SELECT P.postcode,
                    3959 * 2 * ASIN(SQRT( POWER(SIN((:latitude: - P.lat) * pi()/180/2), 2) + COS(:latitude: * pi()/180) * COS(P.lat * pi()/180) * POWER(SIN((:longitude: - P.lon) * pi()/180/2), 2) )) AS distance FROM Postcodes P
                    WHERE P.lat BETWEEN (:latitude: - (:miles:/69)) AND (:latitude: + (:miles:/69))
                AND P.lon BETWEEN (:longitude: - :miles:/ABS(COS(RADIANS(:latitude:)) * 69)) AND (:longitude: + :miles:/ABS(COS(RADIANS(:latitude:)) * 69))
                HAVING distance < :miles:";
        $result = $this->modelsManager->executeQuery($phql, array(
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "miles" => $this->distance / 1.609344
        ));

        /* Old code
		$query = $this->modelsManager->createQuery("SELECT p.postcode FROM Postcodes p WHERE postcode_dist($this->postcode, p.postcode) <= $this->distance");
		$result = $query->execute();
        */

        $pool = array();
		foreach($result as $postcode){
			$pool[] = strlen($postcode->postcode) < 4 ? '0' . $postcode->postcode : $postcode->postcode;
		}
        # return $pool; # Debug
        $this->pool = json_encode($pool);
        $this->save();
        return count($pool);
    }
}
