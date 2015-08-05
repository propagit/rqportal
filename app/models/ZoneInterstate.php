<?php

class ZoneInterstate extends BaseModel
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

    public function toArray($columns = NULL)
    {
        $zone = parent::toArray();
        $zone['circle1'] = $this->drawCircle1();
        $zone['circle2'] = $this->drawCircle2();
        $zone['path'] = $this->drawPath();
        return $zone;
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
        /*
			Code in the comments are from Old Phalcon 1.3 kept for ref on why this is failing
		*/
	    # Pool 1
        #$result = $this->db->query("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode1, p.postcode) <= $this->distance1");
		$query = $this->modelsManager->createQuery("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode1, p.postcode) <= $this->distance1");
		$result = $query->execute();
		
        $pool1 = array();
        /* 
		$result->setFetchMode(Phalcon\Db::FETCH_OBJ);
        while($postcode = $result->fetch()) {
            $pool1[] = $postcode->postcode;
        }*/
        foreach($result as $postcode) {
            $pool1[] = $postcode->postcode;
        }
        $this->pool1 = json_encode($pool1);

        # Pool 2
		$query = $this->modelsManager->createQuery("SELECT p.postcode FROM postcodes p WHERE postcode_dist($this->postcode2, p.postcode) <= $this->distance2");
		$result = $query->execute();
		
        $pool2 = array();
		foreach($result as $postcode){
			$pool2[] = $postcode->postcode;	
		}
        $this->pool2 = json_encode($pool2);

        $this->save();
        return count($pool1) + count($pool2);
    }

}
