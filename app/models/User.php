<?php

class User extends \Phalcon\Mvc\Model
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
    public $status;

    const PENDING = 0;
    const APPROVED = 1;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $level;

    const SUPPLIER = 1;
    const ADMIN = 9;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'status' => 'status',
            'username' => 'username',
            'password' => 'password',
            'level' => 'level'
        );
    }

}
