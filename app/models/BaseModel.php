<?php

class BaseModel extends Phalcon\Mvc\Model
{

    public $db;

    public function initialize()
    {
        $this->db = $this->getDi()->getShared('db');
    }
}
