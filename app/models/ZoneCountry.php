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

    public function toJson()
    {
        $local = ZoneLocal::findFirst("id = " . $this->local_id);
        return array(
            'id' => $this->id,
            'postcode' => $local->postcode,
            'local' => $local->distance,
            'distance' => $this->distance
        );
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

        $result = $this->db->query("SELECT p.postcode FROM postcodes p WHERE postcode_dist($local->postcode, p.postcode) <= $this->distance");

        $pool = array();
        $result->setFetchMode(Phalcon\Db::FETCH_OBJ);
        while($postcode = $result->fetch()) {
            $pool[] = $postcode->postcode;
        }
        $this->pool_local = $local->pool;
        $this->pool_country = json_encode($pool);
        $this->save();
        return count($pool);
    }
}
