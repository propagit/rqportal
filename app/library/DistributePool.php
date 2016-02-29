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
                    case 'reject': $this->rejectSupplier($job_id);
                        break;
                    case 'create_invoice': $this->createInvoice($job_id);
                        break;
                    case 'process_invoice': $this->processInvoice($job_id);
                        break;
                    case 'generate_invoice': $this->generateInvoice($job_id);
                        break;
                    case 'email_invoice': $this->emailInvoice($job_id);
                        break;
                    case 'new_applicant': $this->emailNewApplicant($job_id);
                        break;
                    case 'reset_instruction': $this->emailResetInstruction($job_id);
                        break;
                }
            }
            $job->delete();
        }
    }

    public function emailResetInstruction($user_id)
    {
        if (!$user_id) { return false; }
        $user = User::findFirst($user_id);
        if (!$user) { return false; }
        $supplier = Supplier::findFirstByUserId($user_id);
        if (!$supplier) { return false; }

        $this->mail->send(
            array($supplier['email'] => $supplier['name']),
            'Reset Your Password',
            'reset_password',
            array(
                'name' => $supplier['name'],
                'resetUrl' => '/reset/confirm/' . $user->id . '/' .  $user->reset_key
            )
        );
    }

    public function emailInvoice($data)
    {
        $id = $data['id'];
        if (!$id) { return false; }
        $invoice = Invoice::findFirst($id);
        if (!$invoice) { return false; }
        $invoice = $invoice->toArray();
        $email = $invoice['supplier']['email'];
        if (isset($data['email'])) { $email = $data['email']; }

        $this->mail->send(
            array($email => $invoice['supplier']['name']),
                'Invoice From Removalist Quote',
                'invoice',
                array(
                    'name' => $invoice['supplier']['name'],
                    'attachment' => __DIR__ . '/../../public/files/invoice' . $id . '.pdf'
                )
        );
    }

    public function emailNewApplicant($id)
    {
        if (!$id) { return false; }
        $supplier = Supplier::findFirst($id);
        if (!$supplier) { return false; }

        $this->mail->send(
            array('sales@removalistquote.com.au' => 'Team'), # hard code for now
            'New Member Sign Up',
            'new_applicant',
            array(
                'name' => $supplier->name,
                'business' => $supplier->business,
                'company' => $supplier->company,
                'abn_acn' => $supplier->abn_acn,
                'address' => $supplier->address,
                'suburb' => $supplier->suburb,
                'state' => $supplier->state,
                'postcode' => $supplier->postcode,
                'phone' => $supplier->phone,
                'email' => $supplier->email,
                'website' => $supplier->website,
                'about' => $supplier->about
            )
        );
    }

    public function generateInvoice($id)
    {
        if (!$id) { return false; }
        $invoice = Invoice::findFirst($id);
        if (!$invoice) { return false; }

        $data['invoice'] = $invoice->toArray();
        $data['baseUrl'] = $this->config->application->publicUrl;
        $html = $this->view->getRender('billing', 'invoice_pdf', $data);
        $pdf = new mPDF();
        $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output(__DIR__ . '/../../public/files/invoice' . $id . '.pdf', "F");
    }

    public function processInvoice($id)
    {
        if (!$id) { return false; }
        $invoice = Invoice::findFirst($id);
        if (!$invoice) { return false; }
        $supplier = Supplier::findFirstByUserId($invoice->user_id);
        if (!$supplier) { return false; }
        if (!$supplier->eway_customer_id) {
            echo 'Supplier has no credit card information';
            return false;
        }

        # If the supplier is inactive is not procceding with the payment
        if ($supplier->status == Supplier::INACTIVED) { return false; }

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
            if ($invoice->eway_trxn_status == 'True') {
                $invoice->status = Invoice::PAID;
                $invoice->paid_on = date('Y-m-d H:i:s');
                echo 'Payment transaction approved';
            } else {
				# payment failed de activate this account
					#$supplier = Supplier::findFirst($invoice->user_id); //Zack changed, set up the same as BillingController.php
                    $supplier = Supplier::findFirstByUserId($invoice->user_id);
                    $supplier->status = Supplier::INACTIVED;
                    $supplier->save();
					if ($supplier->user_id)
					{
						$user = User::findFirst($supplier->user_id);
						$user->status = User::INACTIVED;
						$user->save();
					}
                echo $invoice->eway_trxn_msg;
            }
            $invoice->save();
        } catch(Exception $e) {
            echo $e->getMessage();
        }

    }

    public function createInvoice($user_id)
    {
        if (!$user_id) { return false; }

        # We check if this user is currently has unpaid invoice, then do not create a new one
        $invoices = Invoice::find(array(
            "user_id = :user_id: AND status = :status:",
            "bind" => array(
                "user_id" => $user_id,
                "status" => Invoice::UNPAID
            )
        ));
        if (count($invoices) > 0) {
            return false;
        }

        $quotes = Quote::find("user_id = $user_id AND invoice_id is NULL");
        if (!$quotes || count($quotes) == 0) { return false; }
        $price_per_quote = Setting::findFirstByName(Setting::PRICE_PER_QUOTE);
        $invoice = new Invoice();
        $invoice->user_id = $user_id;
        $invoice->price_per_quote = $price_per_quote->value;
        $invoice->amount = count($quotes) * floatval($price_per_quote->value);
        $invoice->status = Invoice::UNPAID;
        $invoice->created_on = date('Y-m-d H:i:s');
        $invoice->due_date = date('Y-m-d H:i:s');
        if ($invoice->save())
        {
            foreach($quotes as $quote)
            {
                $quote->invoice_id = $invoice->id;
                $quote->save();
                if ($quote->free)
                {
                    $invoice->amount = $invoice->amount - floatval($price_per_quote->value);
                }

            }
            $invoice->save();
            # Auto process payment
            $this->queue->put(array(
                'process_invoice' => $invoice->id
            ));

            # Generate invoice in PDF
            $this->queue->put(array(
                'generate_invoice' => $invoice->id
            ));

            # Send email to supplier
            $this->queue->put(array(
                'email_invoice' => array(
                    'id' => $invoice->id
                )
            ));

            echo "Invoice $invoice->id created automatically";
        }
        else
        {
            var_dump($invoice->getMessages());
        }
    }

    public function rejectSupplier($id)
    {
        if (!$id) { return false; }
        $supplier = Supplier::findFirst($id);
        if (!$supplier) { return false; }
        $this->mail->send(
            array($supplier->email => $supplier->name),
            'Member Application - Rejected',
            'reject',
            array('name' => $supplier->name)
        );
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
        echo "Location populated postcode successfully!";
    }

    public function populateLocal($id)
    {
        if (!$id) { return false; }
        $zone = ZoneLocal::findFirst($id);
        if ($zone) {
            $zone->generatePool();
            echo "Local Zone populated postcode successfully!";
        }
    }

    public function populateCountry($id)
    {
        if (!$id) { return false; }
        $zone = ZoneCountry::findFirst($id);
        if ($zone) {
            $zone->generatePool();
            echo "Country Zone populated postcode successfully!";
        }
    }

    public function populateInterstate($id)
    {
        if (!$id) { return false; }
        $zone = ZoneInterstate::findFirst($id);
        if ($zone) {
            $zone->generatePool();
            echo "Interstate Zone populated postcode successfully!";
        }
    }

    public function distributeRemoval($id)
    {
        # First check if auto allocate quote option is ON
        $auto_allocate_quote = Setting::findFirstByName(Setting::AUTO_ALLOCATE_QUOTE);
        if ($auto_allocate_quote->value == 0)
        {
            return false;
        }

        # Second, check if removal ID is passed
        if (!$id) {
            return false;
        }
        # Get the removal
        $removal = Removal::findFirst($id);

        if (!$removal) {
            return false;
        }

		#check if this is a domestic removal
		if($removal->is_international == 'no'){
			$from = Postcodes::findFirstByPostcode($removal->from_postcode);
			$to = Postcodes::findFirstByPostcode($removal->to_postcode);

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
		}else{
			# international removal
			$from = $removal->from_country;
			$to = $removal->to_country;
			# International Suppliers
			$suppliers = SupplierFilter::find("name = 'international'
					AND value = 'yes'");
			foreach($suppliers as $supplier) {
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
        $supplier_per_quote = Setting::findFirstByName(Setting::SUPPLIER_PER_QUOTE);

        foreach($users_with_quote as $user_id => $quote_number) {
            $supplier = Supplier::findFirstByUserId($user_id);

            # Check the supplier filter first
            $filters = SupplierFilter::find("user_id = $user_id");
            $matched = true;
            foreach($filters as $filter)
            {
                /*if ($filter->name == 'bedrooms') {
                    if (($filter->value == Removal::UNDER_THREE && intval($removal->bedrooms) >= 3)
                    || ($filter->value == Removal::THREE_PLUS && intval($removal->bedrooms) < 3)) {
                        $matched = false;
                    }
                }*/

				/*if ($filter->name == 'bedrooms') {
                    if (($filter->value == Removal::TWO_PLUS && intval($removal->bedrooms) < 2)) {
                        $matched = false;
                    }
                }*/
            }

            # Check if this supplier already received this quote
            $quote = Quote::findFirst(array(
                "job_id = :job_id: AND user_id = :user_id:",
                "bind" => array(
                    "job_id" => $removal->id,
                    "user_id" => $user_id
                )
            ));

            if ($supplier->status == Supplier::APPROVED && $matched
                && $count < $supplier_per_quote->value && !$quote) {
                $quote = new Quote();
                $quote->job_type = Quote::REMOVAL;
                $quote->job_id = $removal->id;
                $quote->user_id = $user_id;
                $quote->status = 0;
                $quote->free = ($supplier->free) ? 1 : 0;
                $quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($quote->save()) {

                    # Send new quote notification to supplier
                    $emails = array();
                    if ($supplier->email_quote_cc) {
                        $emails = array_map('trim', explode(',', $supplier->email_quote_cc));
                    }
                    $emails[] = $supplier->email;


                    $this->mail->send(
                        $emails,
                        'New Removalist Job',
                        'new_removal',
                        array(
                            'removal' => $removal,
                            'from' => $from,
                            'to' => $to
                        )
                    );

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
            $quote->free = 0;
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
        # First check if auto allocate quote option is ON
        $auto_allocate_quote = Setting::findFirstByName(Setting::AUTO_ALLOCATE_QUOTE);
        if ($auto_allocate_quote->value == 0)
        {
            return false;
        }

        # Second check if the storage ID is passed
        if (!$id) {
            return false;
        }
        # Get the storage
        $storage = Storage::findFirst($id);
        $pickup = Postcodes::findFirstByPostcode($storage->pickup_postcode);

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
        $supplier_per_quote = Setting::findFirstByName(Setting::SUPPLIER_PER_QUOTE);
        foreach($users_with_quote as $user_id => $quote_number) {

            # Check if this supplier already received this quote
            $quote = Quote::findFirst(array(
                "job_id = :job_id: AND user_id = :user_id:",
                "bind" => array(
                    "job_id" => $storage->id,
                    "user_id" => $user_id
                )
            ));

            $supplier = Supplier::findFirstByUserId($user_id);
            if ($supplier->status == Supplier::APPROVED &&
                    $count < $supplier_per_quote->value && !$quote) {
                $quote = new Quote();
                $quote->job_type = Quote::STORAGE;
                $quote->job_id = $storage->id;
                $quote->user_id = $user_id;
                $quote->status = 0;
                $quote->free = ($supplier->free) ? 1 : 0;
                $quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($quote->save()) {

                    # Send new quote notification to supplier
                    $emails = array();
                    if ($supplier->email_quote_cc) {
                        $emails = array_map('trim', explode(',', $supplier->email_quote_cc));
                    }
                    $emails[] = $supplier->email;


                    $this->mail->send(
                        $emails,
                        'New Removalist Job',
                        'new_storage',
                        array(
                            'storage' => $storage,
                            'pickup' => $pickup
                        )
                    );
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
            $quote->free = 0;
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
