<?php

class RemovalInventory extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $removal_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $items;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'removal_inventory';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RemovalInventory[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RemovalInventory
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
