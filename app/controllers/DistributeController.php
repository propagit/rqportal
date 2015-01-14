<?php

class DistributeController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function storageAction($id=0)
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


        $count = 1;
        foreach($users_with_quote as $user_id => $quote_number) {

            if ($count > $this->config->supplierPerQuote) {
                echo 'More than 3';
                break;
            }
            $quote = new Quote();
            $quote->job_type = Quote::STORAGE;
            $quote->job_id = $storage->id;
            $quote->user_id = $user_id;
            $quote->status = 0;
            $quote->created_on = new Phalcon\Db\RawValue('now()');
            if ($quote->save()) {
                $count++;
                echo 'Storage quote sent to ' . $user_id . '<br />';
            } else {
                var_dump($quote->getMessages());
            }
        }

    }

    public function removalAction($id=0)
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


        $count = 1;
        foreach($users_with_quote as $user_id => $quote_number) {

            if ($count > $this->config->supplierPerQuote) {
                echo 'More than 3';
                break;
            }
            $quote = new Quote();
            $quote->job_type = Quote::REMOVAL;
            $quote->job_id = $removal->id;
            $quote->user_id = $user_id;
            $quote->status = 0;
            $quote->created_on = new Phalcon\Db\RawValue('now()');
            if ($quote->save()) {
                $count++;
                echo 'Removal quote sent to ' . $user_id . '<br />';
            } else {
                var_dump($quote->getMessages());
            }
        }

    }

}

