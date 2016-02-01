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
     * @var string
     */
    public $lines;

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
    public $eway_trxn_status;

    /**
     *
     * @var string
     */
    public $eway_trxn_msg;

    /**
     *
     * @var string
     */
    public $eway_trxn_number;

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
            'lines' => 'lines',
            'amount' => 'amount',
            'status' => 'status',
            'created_on' => 'created_on',
            'due_date' => 'due_date',
            'paid_on' => 'paid_on',
            'eway_trxn_status' => 'eway_trxn_status',
            'eway_trxn_msg' => 'eway_trxn_msg',
            'eway_trxn_number' => 'eway_trxn_number',
            'deleted_on' => 'deleted_on'
        );
    }

    public function toArray($columns = NULL)
    {
        $invoice = parent::toArray();
        $invoice['created_on'] = strtotime($this->created_on) * 1000;
        $invoice['due_date'] = strtotime($this->due_date) * 1000;
        $supplier = Supplier::findFirstByUserId($this->user_id);
        $invoice['supplier'] = $supplier->toArray();
        $removals = array();
        $storages = array();
        $quotes = Quote::find("invoice_id = $this->id");
        $free = 0;
        // foreach($quotes as $quote)
        // {
        //     if ($quote->job_type == 'removal')
        //     {
        //         $removal = $quote->getRemoval();
        //         $removal['status'] = $quote->status;
        //         $removal['free'] = $quote->free;
        //         $removal['created_on'] = strtotime($quote->created_on) * 1000;
        //         $removal['moving_date'] = strtotime($removal['moving_date']) * 1000;
        //         $removals[] = $removal;
        //     }
        //     else
        //     {
        //         $storage = $quote->getStorage();
        //         $storage['status'] = $quote->status;
        //         $storage['free'] = $quote->free;
        //         $storage['created_on'] = strtotime($quote->created_on) * 1000;
        //         $storages[] = $storage;
        //     }
        //     if ($quote->free)
        //     {
        //         $free++;
        //     }
        // }
        // $invoice['free'] = $free;
        // $invoice['removals'] = $removals;
        // $invoice['storages'] = $storages;
        // $invoice['lines'] = json_decode($invoice['lines']);
        return $invoice;
    }

    public static function getStatus()
    {
        return array(
            Invoice::UNPAID => 'Unpaid',
            Invoice::PAID => 'Paid'
        );
    }
}
