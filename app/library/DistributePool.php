<?php

use Phalcon\Di\Injectable;

class DistributePool extends Injectable
{
    /**
     *  Check the queue from Beanstalk and distribute the quote to suppliers
     */
    public function consumeQueue()
    {
        while(($job = $this->queue->peekReady()) !== false)
        {

            $message = $job->getBody();
            foreach($message as $job_type => $job_id) {
                switch($job_type) {
                    case 'location': $this->populateLocation($job_id);
                        break;
                    case 'local': $this->populateLocal($job_id);
                        break;
                    case 'country': $this->populateCountry($job_id);
                        break;
                    case 'interstate': $this->populateInterstate($job_id);
                        break;
                    case 'removal': $this->distributeRemoval($job_id);
                        break;
                    case 'storage': $this->distributeStorage($job_id);
                        break;
                    case 'activation': $this->sendActivation($job_id);
                        break;
                    case 'generate_invoice': $this->generateInvoice($job_id);
                        break;
                    case 'email_invoice': $this->emailInvoice($job_id);
                        break;
                }
            }
            $job->delete();
        }
    }

    public function emailInvoice($data)
    {
        $id = $data['id'];
        if (!$id) { return false; }
        $invoice = Invoice::findFirst($id)->toArray();
        $email = $invoice['supplier']['email'];
        if (isset($data['email'])) { $email = $data['email']; }

        $this->mail->send(
            array($email => $invoice['supplier']['name']),
                'Invoice From Removalist Quote',
                'invoice',
                array('name' => $invoice['supplier']['name'])
        );
    }

    public function generateInvoice($id)
    {
        // $this->view->disable();
        $data['invoice'] = Invoice::findFirst($id)->toArray();
        $html = $this->view->getRender('billing', 'invoice_pdf', $data);
        $pdf = new mPDF();
        $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output(__DIR__ . '/../../public/files/invoice' . $id . '.pdf', "F");
    }

    public function sendActivation($supplierId)
    {
        if (!$supplierId) { return false; }
        $supplier = Supplier::findFirst($supplierId);
        if (!$supplier) { return false; }
        $this->mail->send(
            array($supplier->email => $supplier->name),
                'Account Activation',
                'activation',
                array('name' => $supplier->name,
                    'activationUrl' =>
                    '/applicant/register/' . $supplier->id . '/' . $supplier->activation_key)
        );
    }

    public function populateLocation($userId)
    {
        if (!$userId)
        {
            return false;
        }
        $zone_local = ZoneLocal::find("user_id = $userId");
        foreach($zone_local as $zone) {
            $zone->generatePool();
        }
        $zone_country = ZoneCountry::find("user_id = $userId");
        foreach($zone_country as $zone) {
            $zone->generatePool();
        }
        $zone_interstate = ZoneInterstate::find("user_id = $userId");
        foreach($zone_interstate as $zone) {
            $zone->generatePool();
        }
    }

    public function populateLocal($id)
    {
        if (!$id) { return false; }
        $zone = ZoneLocal::findFirst($id);
        if ($zone) {
            $zone->generatePool();
        }
    }

    public function populateCountry($id)
    {
        if (!$id) { return false; }
        $zone = ZoneCountry::findFirst($id);
        if ($zone) {
            $zone->generatePool();
        }
    }

    public function populateInterstate($id)
    {
        if (!$id) { return false; }
        $zone = ZoneInterstate::findFirst($id);
        if ($zone) {
            $zone->generatePool();
        }
    }

    public function distributeRemoval($id)
    {
        if (!$id) {
            return false;
        }
        # Get the removal
        $removal = Removal::findFirst($id);

        # Check suppliers who are able to provide this removal
        $users = array();

        # Local Zone
        $suppliers = ZoneLocal::find("pool LIKE '%$removal->from_postcode%'
                AND pool LIKE '%$removal->to_postcode%'");
        foreach($suppliers as $supplier) {
            $users[] = $supplier->user_id;
        }

        # Country Zone
        $suppliers = ZoneCountry::find("(pool_local LIKE '%$removal->from_postcode%'
                AND pool_country LIKE '%$removal->to_postcode%') OR
                (pool_country LIKE '%$removal->from_postcode%'
                AND pool_local LIKE '%$removal->to_postcode%')");
        foreach($suppliers as $supplier) {
            if (!in_array($supplier->user_id, $users)) {
                $users[] = $supplier->user_id;
            }
        }

        # Interstate
        $suppliers = ZoneInterstate::find("(pool1 LIKE '%$removal->from_postcode%'
                AND pool2 LIKE '%$removal->to_postcode%') OR
                (pool2 LIKE '%$removal->from_postcode%'
                AND pool1 LIKE '%$removal->to_postcode%')");
        foreach($suppliers as $supplier) {
            if (!in_array($supplier->user_id, $users)) {
                $users[] = $supplier->user_id;
            }
        }

        # Get quote of the day for each user
        $users_with_quote = array();

        $today = date('Y-m-d');
        foreach($users as $user_id) {
            $today_quotes = Quote::find("user_id = $user_id
                    AND created_on LIKE '$today%'");

            $users_with_quote[$user_id] = count($today_quotes);
        }
        # Sort by today quote in ascending order
        asort($users_with_quote);


        $count = 0;
        foreach($users_with_quote as $user_id => $quote_number) {

            if ($count < $this->config->supplierPerQuote) {
                $quote = new Quote();
                $quote->job_type = Quote::REMOVAL;
                $quote->job_id = $removal->id;
                $quote->user_id = $user_id;
                $quote->status = 0;
                $quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($quote->save()) {
                    $count++;
                    echo 'Removal quote sent to ' . $user_id . PHP_EOL;
                } else {
                    var_dump($quote->getMessages());
                }
            }

        }

        if ($count == 0) { # The quote has not been sent to any supplier
            $quote = new Quote();
            $quote->job_type = Quote::REMOVAL;
            $quote->job_id = $removal->id;
            $quote->user_id = 0;
            $quote->status = 0;
            $quote->created_on = new Phalcon\Db\RawValue('now()');
            if ($quote->save()) {
                $count++;
                echo 'Removal quote created but not allocated';
            } else {
                var_dump($quote->getMessages());
            }
        }

    }

    public function distributeStorage($id)
    {
        if (!$id) {
            return false;
        }
        # Get the storage
        $storage = Storage::findFirst($id);

        # Check suppliers who are able to provide this storage
        $users = array();

        # Local Zone
        $suppliers = ZoneLocal::find("pool LIKE '%$storage->pickup_postcode%'");
        foreach($suppliers as $supplier) {
            $users[] = $supplier->user_id;
        }

        # Get quote of the day for each user
        $users_with_quote = array();

        $today = date('Y-m-d');
        foreach($users as $user_id) {
            $today_quotes = Quote::find("user_id = $user_id
                    AND created_on LIKE '$today%'");

            $users_with_quote[$user_id] = count($today_quotes);
        }
        # Sort by today quote in ascending order
        asort($users_with_quote);


        $count = 0;
        foreach($users_with_quote as $user_id => $quote_number) {

            if ($count < $this->config->supplierPerQuote) {
                $quote = new Quote();
                $quote->job_type = Quote::STORAGE;
                $quote->job_id = $storage->id;
                $quote->user_id = $user_id;
                $quote->status = 0;
                $quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($quote->save()) {
                    $count++;
                    echo 'Storage quote sent to ' . $user_id . PHP_EOL;
                } else {
                    var_dump($quote->getMessages());
                }
            }

        }

        if ($count == 0) { # The quote has not been sent to any supplier
            $quote = new Quote();
            $quote->job_type = Quote::STORAGE;
            $quote->job_id = $storage->id;
            $quote->user_id = 0;
            $quote->status = 0;
            $quote->created_on = new Phalcon\Db\RawValue('now()');
            if ($quote->save()) {
                $count++;
                echo 'Storage quote created but not allocated';
            } else {
                var_dump($quote->getMessages());
            }
        }

    }
}
