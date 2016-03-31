<?php

use Phalcon\DI;

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
        foreach($quotes as $quote)
        {
            if ($quote->job_type == 'removal')
            {
                $removal = $quote->getRemoval();
                if ($removal) {
                    $removal['status'] = $quote->status;
                    $removal['free'] = $quote->free;
                    $removal['created_on'] = strtotime($quote->created_on) * 1000;
                    $removal['moving_date'] = strtotime($removal['moving_date']) * 1000;
                    $removals[] = $removal;
                }

            }
            else
            {
                $storage = $quote->getStorage();
                $storage['status'] = $quote->status;
                $storage['free'] = $quote->free;
                $storage['created_on'] = strtotime($quote->created_on) * 1000;
                $storages[] = $storage;
            }
            if ($quote->free)
            {
                $free++;
            }
        }
        $invoice['free'] = $free;
        if ($invoice['id'] != 544) {
            $invoice['removals'] = $removals;
        }
        $invoice['storages'] = $storages;
        $invoice['lines'] = json_decode($invoice['lines']);
        return $invoice;
    }

    public static function getStatus()
    {
        return array(
            Invoice::UNPAID => 'Unpaid',
            Invoice::PAID => 'Paid'
        );
    }

    public function process()
    {
        $supplier = Supplier::findFirstByUserId($this->user_id);
        if (!$supplier) {
            return array(
                'success' => false,
                'msg' => 'Supplier profile not exists'
            );
        }
        if (!$supplier->eway_customer_id) {
            $supplier->status = Supplier::INACTIVED;
            $supplier->save();
            return array(
                'success' => false,
                'msg' => 'Supplier does not have eway customer id'
            );
        }
        try {
            $client = new SoapClient(DI::getDefault()->getConfig()->eway->endpoint, array('trace' => 1));
            $header = new SoapHeader(DI::getDefault()->getConfig()->eway->namespace, 'eWAYHeader', DI::getDefault()->getConfig()->eway->headers);
            $client->__setSoapHeaders(array($header));
            $eway_invoice = array(
                'managedCustomerID' => $supplier->eway_customer_id,
                'amount' => $this->amount * 100,
                'invoiceReference' => $this->id,
                'invoiceDescription' => 'RemovalistQuote'
            );
            $result = $client->ProcessPayment($eway_invoice);
            $this->eway_trxn_status = $result->ewayResponse->ewayTrxnStatus;
            $this->eway_trxn_msg = $result->ewayResponse->ewayTrxnError;
            $this->eway_trxn_number = $result->ewayResponse->ewayTrxnNumber;
            $this->save();
            if ($this->eway_trxn_status == 'True') {
                $this->status = Invoice::PAID;
                $this->paid_on = date('Y-m-d H:i:s');
                $this->save();
                $this->emailToSupplier();
                return array(
                    'success' => true,
                    'msg' => $this->eway_trxn_number
                );
            } else {
                # payment failed de activate this account
                $supplier->status = Supplier::INACTIVED;
                $supplier->save();
                return array(
                    'success' => false,
                    'msg' => $this->eway_trxn_msg
                );
            }
        } catch(Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    public function emailToSupplier() {
        $supplier = Supplier::findFirstByUserId($this->user_id);
        if (!$supplier) { return; }

        # Generate PDF
        $html = DI::getDefault()->getView()->getRender('billing', 'invoice_pdf', array(
            'invoice' => $this->toArray(),
            'baseUrl' => DI::getDefault()->getConfig()->application->publicUrl
        ));
        $pdf = new mPDF();
        $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output(__DIR__ . '/../../public/files/invoice' . $this->id . '.pdf', "F");

        # Send email to supplier
        DI::getDefault()->getMail()->send(
            // array('nam@propagate.com.au' => 'Nam Nguyen'),
            array($supplier->email => $supplier->name),
            'Invoice From Removalist Quote',
            'invoice',
            array(
                'name' => $supplier->name,
                'attachment' => __DIR__ . '/../../public/files/invoice' . $this->id . '.pdf'
            )
        );
    }
}
