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

            # Auto process payment
            $supplier = Supplier::findFirstByUserId($this->id);
            if (!$supplier) {
                return;
            }
            if (!$supplier->eway_customer_id) {
                $supplier->status = Supplier::INACTIVED;
                $supplier->save();
            }
            if ($supplier->status == Supplier::INACTIVED) {
                return;
            }

            try {
                $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
                $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
                $client->__setSoapHeaders(array($header));
                $eway_invoice = array(
                    'managedCustomerID' => $supplier->eway_customer_id,
                    'amount' => $invoice->amount * 100,
                    'invoiceReference' => $invoice->id,
                    'invoiceDescription' => 'RemovalistQuote'
                );
                $result = $client->ProcessPayment($eway_invoice);
                $invoice->eway_trxn_status = $result->ewayResponse->ewayTrxnStatus;
                $invoice->eway_trxn_msg = $result->ewayResponse->ewayTrxnError;
                $invoice->eway_trxn_number = $result->ewayResponse->ewayTrxnNumber;
                $invoice->save();
                if ($invoice->eway_trxn_status == 'True') {
                    $invoice->status = Invoice::PAID;
                    $invoice->paid_on = date('Y-m-d H:i:s');
                    $invoice->save();
                    // echo 'Payment transaction approved';

                    # Generate PDF
                    $html = $this->view->getRender('billing', 'invoice_pdf', array(
                        'invoice' => $invoice->toArray(),
                        'baseUrl' => $this->config->application->publicUrl
                    ));
                    $pdf = new mPDF();
                    $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
                    $pdf->WriteHTML($stylesheet,1);
                    $pdf->WriteHTML($html, 2);
                    $pdf->Output(__DIR__ . '/../../public/files/invoice' . $invoice->id . '.pdf', "F");

                    # Send email to supplier
                    $this->mail->send(
                        array($supplier->email => $supplier->name),
                        'Invoice From Removalist Quote',
                        'invoice',
                        array(
                            'name' => $supplier->name,
                            'attachment' => __DIR__ . '/../../public/files/invoice' . $invoice->id . '.pdf'
                        )
                    );

                } else {
                    # payment failed de activate this account
                    $supplier->status = Supplier::INACTIVED;
                    $supplier->save();
                    // echo $invoice->eway_trxn_msg;
                }
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function processInvoice($invoice_id) {
        $invoice = Invoice::findFirst($invoice_id);
        if (!$invoice) {
            return json_encode(array(
                'success' => false,
                'msg' => 'Invoice not found'
            ));
        }
        $supplier = Supplier::findFirstByUserId($this->id);
        if (!$supplier) {
            return json_encode(array(
                'success' => false,
                'msg' => 'Supplier profile not exists'
            ));
        }
        if (!$supplier->eway_customer_id) {
            $supplier->status = Supplier::INACTIVED;
            $supplier->save();
            return json_encode(array(
                'success' => false,
                'msg' => 'Supplier does not have eway customer id'
            ));
        }
        if ($supplier->status == Supplier::INACTIVED) {
            return json_encode(array(
                'success' => false,
                'msg' => 'Supplier is inactive'
            ));
        }

        try {
            $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
            $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
            $client->__setSoapHeaders(array($header));
            $eway_invoice = array(
                'managedCustomerID' => $supplier->eway_customer_id,
                'amount' => $invoice->amount * 100,
                'invoiceReference' => $invoice->id,
                'invoiceDescription' => 'RemovalistQuote'
            );
            $result = $client->ProcessPayment($eway_invoice);
            $invoice->eway_trxn_status = $result->ewayResponse->ewayTrxnStatus;
            $invoice->eway_trxn_msg = $result->ewayResponse->ewayTrxnError;
            $invoice->eway_trxn_number = $result->ewayResponse->ewayTrxnNumber;
            $invoice->save();
            if ($invoice->eway_trxn_status == 'True') {
                $invoice->status = Invoice::PAID;
                $invoice->paid_on = date('Y-m-d H:i:s');
                $invoice->save();
                return json_encode(array(
                    'success' => true,
                    'msg' => $invoice->eway_trxn_number
                ));
            } else {
                # payment failed de activate this account
                $supplier->status = Supplier::INACTIVED;
                $supplier->save();
                return json_encode(array(
                    'success' => true,
                    'msg' => $invoice->eway_trxn_msg
                ));
            }
        } catch(Exception $e) {
            return json_encode(array(
                'success' => true,
                'msg' => $e->getMessage()
            ));
        }
    }
}
