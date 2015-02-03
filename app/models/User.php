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

    const INACTIVED = -1;
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

    public function countNewQuotes()
    {
        $conditions = "user_id = :user_id: AND status = :status:";
        $parameters = array(
            'user_id' => $this->id,
            'status' => Quote::FRESH
        );
        $quotes = Quote::find(array(
            $conditions,
            "bind" => $parameters
        ));
        return count($quotes);
    }
}
