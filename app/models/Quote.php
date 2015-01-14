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
    public $job_type;

    const REMOVAL = 'removal';
    const STORAGE = 'storage';

    /**
     *
     * @var integer
     */
    public $job_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $status;

    const LOSS = 1;
    const WON = 2;

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
            'job_type' => 'job_type',
            'job_id' => 'job_id',
            'user_id' => 'user_id',
            'status' => 'status',
            'created_on' => 'created_on'
        );
    }

}
