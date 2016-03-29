<?php

class QuoteTask extends \Phalcon\CLI\Task
{
    public function distributeAction()
    {
        # First check if auto allocate quote option is ON
        $auto_allocate_quote = Setting::findFirstByName(Setting::AUTO_ALLOCATE_QUOTE);
        if ($auto_allocate_quote->value == 0) { return false; }

        # Get all the quotes that haven't been distributed
        $removals = Removal::findByAutoDistributed(0);
        if (count($removals) > 0) {
            foreach($removals as $removal) {
                $this->_distributeRemoval($removal);
            }
        }

        $storages = Storage::findByAutoDistributed(0);
        if (count($storages) > 0) {
            foreach($storages as $storage) {
                $this->_distributeStorage($storage);
            }
        }
    }

    private function _distributeStorage($storage) {
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
                echo 'Storage quote created but not allocated' . PHP_EOL;
            } else {
                var_dump($quote->getMessages());
            }
        }
        $storage->auto_distributed = 1;
        $storage->save();
    }

    private function _distributeRemoval($removal) {
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
                echo 'Removal quote created but not allocated' . PHP_EOL;
            } else {
                var_dump($quote->getMessages());
            }
        }
        $removal->auto_distributed = 1;
        $removal->save();
    }
}
