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
            if ($removal) {
                return $removal->toArray();
            }
        }
        return null;
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
        $users = array();
        foreach($quotes as $quote) {
            if (!in_array($quote->user_id, $users)) {
                $users[] = $quote->user_id;
                $supplier = Supplier::findFirstByUserId($quote->user_id);
                if ($supplier) {
                    $supplier = $supplier->toArray();
                    $supplier['quote_status'] = $quote->status;
                    $suppliers[] = $supplier;
                }
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

    # Check if the new invoice should be generated once reaching the threshold
    public function afterCreate()
    {
        if ($this->user_id > 0)
        {
            $conditions = "user_id = $this->user_id AND invoice_id IS NULL AND free = 0";
            $outstanding_quotes = Quote::count($conditions);
            $price_per_quote = Setting::findFirstByName(Setting::PRICE_PER_QUOTE);
            $invoice_threshold = Setting::findFirstByName(Setting::INVOICE_THRESHOLD);
            if ($outstanding_quotes * floatval($price_per_quote->value) >= floatval($invoice_threshold->value))
            {
                $this->getDI()->getQueue()->put(array(
                    'create_invoice' => $this->user_id
                ));
            }
        }
        return true;
    }

}
