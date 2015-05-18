<?php

class Countries extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $abbr;

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
            'id' => 'id', 
            'name' => 'name', 
            'abbr' => 'abbr', 
            'lat' => 'lat', 
            'lon' => 'lon'
        );
    }

}
