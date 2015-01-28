<?php

class Invoice extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $price_per_quote;

    /**
     *
     * @var double
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $status;

    const DELETED = -1;
    const UNPAID = 0;
    const PAID = 1;

    /**
     *
     * @var string
     */
    public $created_on;

    /**
     *
     * @var string
     */
    public $due_date;

    /**
     *
     * @var string
     */
    public $paid_on;

    /**
     *
     * @var string
     */
    public $deleted_on;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'price_per_quote' => 'price_per_quote',
            'amount' => 'amount',
            'status' => 'status',
            'created_on' => 'created_on',
            'due_date' => 'due_date',
            'paid_on' => 'paid_on',
            'deleted_on' => 'deleted_on'
        );
    }

    public function toArray($columns = NULL)
    {
        $invoice = parent::toArray();
        $invoice['created_on'] = strtotime($this->created_on) * 1000;
        $supplier = Supplier::findFirstByUserId($this->user_id);
        $invoice['supplier'] = $supplier->toArray();
        $removals = array();
        $storages = array();
        $quotes = Quote::find("invoice_id = $this->id");
        foreach($quotes as $quote)
        {
            if ($quote->job_type == 'removal')
            {
                $removal = $quote->getRemoval();
                $removal['status'] = $quote->status;
                $removal['free'] = $quote->free;
                $removal['created_on'] = strtotime($quote->created_on) * 1000;
                $removals[] = $removal;
            }
            else
            {
                $storage = $quote->getStorage();
                $storage['status'] = $quote->status;
                $removal['free'] = $quote->free;
                $storage['created_on'] = strtotime($quote->created_on) * 1000;
                $storages[] = $storage;
            }
        }
        $invoice['removals'] = $removals;
        $invoice['storages'] = $storages;
        return $invoice;
    }

}
