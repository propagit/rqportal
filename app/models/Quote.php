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
    public $invoice_id;

    /**
     *
     * @var integer
     */
    public $free;

    /**
     *
     * @var integer
     */
    public $status;

    const FRESH = 0;
    const VIEWED = 1;
    const LOST = 2;
    const WON = 3;

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
            'invoice_id' => 'invoice_id',
            'free' => 'free',
            'status' => 'status',
            'created_on' => 'created_on'
        );
    }

    public function getRemoval()
    {
        if ($this->job_type == Quote::REMOVAL) {
            $removal = Removal::findFirst($this->job_id);
            return $removal->toArray();
        }
        return false;
    }

    public function getStorage()
    {
        if ($this->job_type == Quote::STORAGE) {
            $storage = Storage::findFirst($this->job_id);
            return $storage->toArray();
        }
        return false;
    }

    /**
     * Get suppliers who get the same quote
     */
    public function getSuppliers()
    {
        $conditions = "job_type = :job_type: AND job_id = :job_id:";
        $parameters = array(
            'job_type' => $this->job_type,
            'job_id' => $this->job_id
        );
        $quotes = Quote::find(array(
            $conditions,
            "bind" => $parameters
        ));
        $suppliers = array();
        foreach($quotes as $quote) {
            $supplier = Supplier::findFirstByUserId($quote->user_id);
            if ($supplier) {
                $supplier = $supplier->toArray();
                $supplier['quote_status'] = $quote->status;
                $suppliers[] = $supplier;
            }
        }
        return $suppliers;
    }

    public static function getStatus()
    {
        return array(
            Quote::VIEWED => 'Any',
            Quote::LOST => 'Lost',
            Quote::WON => 'Won'
        );
    }

}
