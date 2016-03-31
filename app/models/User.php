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
     * @var string
     */
    public $reset_key;


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
            'reset_key' => 'reset_key',
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

    public function createInvoice()
    {
        $invoices = Invoice::find(array(
            "user_id = :user_id: AND status = :status:",
            "bind" => array(
                "user_id" => $this->id,
                "status" => Invoice::UNPAID
            )
        ));
        if (count($invoices) > 0) {
            return;
        }

        $quotes = Quote::find(array(
            "user_id = :user_id: AND invoice_id is NULL",
            "bind" => array(
                "user_id" => $this->id
            )
        ));
        if (!$quotes || count($quotes) == 0) {
            return;
        }

        $price_per_quote = Setting::findFirstByName(Setting::PRICE_PER_QUOTE);

        $invoice = new Invoice();
        $invoice->user_id = $this->id;
        $invoice->price_per_quote = $price_per_quote->value;
        $invoice->amount = count($quotes) * floatval($price_per_quote->value);
        $invoice->status = Invoice::UNPAID;
        $invoice->created_on = date('Y-m-d H:i:s');
        $invoice->due_date = date('Y-m-d H:i:s');
        if ($invoice->save()) {
            foreach($quotes as $quote) {
                $quote->invoice_id = $invoice->id;
                $quote->save();

                if ($quote->free) {
                    $invoice->amount = $invoice->amount - floatval($price_per_quote->value);
                }
            }
            $invoice->save();
            $invoice->process();
        }
    }
}
