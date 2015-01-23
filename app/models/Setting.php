<?php

class Setting extends \Phalcon\Mvc\Model
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

    const PRICE_PER_QUOTE = 'price_per_quote';
    const INVOICE_THRESHOLD = 'invoice_threshold';
    const SUPPLIER_PER_QUOTE = 'supplier_per_quote';
    const AUTO_ALLOCATE_QUOTE = 'auto_allocate_quote';

    /**
     *
     * @var string
     */
    public $value;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'name' => 'name',
            'value' => 'value'
        );
    }

}
