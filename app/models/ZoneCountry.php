<?php

class ZoneCountry extends BaseModel
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
    public $local_id;

    /**
     *
     * @var integer
     */
    public $distance;

    /**
     *
     * @var string
     */
    public $pool_local;

    /**
     *
     * @var string
     */
    public $pool_country;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'local_id' => 'local_id',
            'distance' => 'distance',
            'pool_local' => 'pool_local',
            'pool_country' => 'pool_country'
        );
    }

    public function getLocal()
    {
        return ZoneLocal::findFirst("id = " . $this->local_id);
    }

    public function toArray($columns = NULL)
    {
        $zone = parent::toArray();
        $local = ZoneLocal::findFirst("id = " . $this->local_id);
        $zone['local'] = $local->toArray();
        $zone['circle'] = $this->drawCircle();
        $zone['marker'] = $local->drawCircle();
        return $zone;
    }

    public function drawCircle()
    {
        $local = ZoneLocal::findFirst("id = " . $this->local_id);
        return array(
            'id' => $this->id,
            'center' => array(
                'latitude' => $local->latitude,
                'longitude' => $local->longitude
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
        $local = ZoneLocal::findFirst("id = " . $this->local_id);
        return array(
            'id' => $this->id,
            'coords' => array(
                'latitude' => $local->latitude,
                'longitude' => $local->longitude
            )
        );
    }

    public function generatePool()
    {
        $local = ZoneLocal::findFirst("id = " . $this->local_id);

        /* Old code
		$query = $this->modelsManager->createQuery("SELECT p.postcode FROM Postcodes p WHERE postcode_dist($local->postcode, p.postcode) <= $this->distance");
		$result = $query->execute();
        */
       $phql = "SELECT P.postcode,
                    3959 * 2 * ASIN(SQRT( POWER(SIN((:latitude: - P.lat) * pi()/180/2), 2) + COS(:latitude: * pi()/180) * COS(P.lat * pi()/180) * POWER(SIN((:longitude: - P.lon) * pi()/180/2), 2) )) AS distance FROM Postcodes P
                    WHERE P.lat BETWEEN (:latitude: - (:miles:/69)) AND (:latitude: + (:miles:/69))
                AND P.lon BETWEEN (:longitude: - :miles:/ABS(COS(RADIANS(:latitude:)) * 69)) AND (:longitude: + :miles:/ABS(COS(RADIANS(:latitude:)) * 69))
                HAVING distance < :miles:";
        $result = $this->modelsManager->executeQuery($phql, array(
            "latitude" => $local->latitude,
            "longitude" => $local->longitude,
            "miles" => $this->distance / 1.609344
        ));


        $pool = array();
		foreach($result as $postcode){
			$pool[] = strlen($postcode->postcode) < 4 ? '0' . $postcode->postcode : $postcode->postcode;
		}
        $this->pool_local = $local->pool;
        $this->pool_country = json_encode($pool);
        $this->save();
        return count($pool);
    }
}
