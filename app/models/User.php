<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends Model
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

    /**
     * Validate that username are unique across users
     */
    public function validation()
    {
        $this->validate(new Uniqueness(array(
            "field" => "username",
            "message" => "The username is already registered"
        )));

        return $this->validationHasFailed() != true;
    }

    public function getTodayQuote()
    {
        $today = date('Y-m-d');
        $quotes = Quote::find("user_id = $this->id");
        return count($quotes);
    }
}
