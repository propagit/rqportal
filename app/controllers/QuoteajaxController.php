<?php

class QuoteajaxController extends ControllerAjax
{

    public function searchAction()
    {
        $request = $this->request->getJsonRawBody();

        $conditions = "1=1";
        if (isset($request->allocated)) {
            if ($request->allocated == 'not_allocated') {
                $conditions .= " AND user_id = 0";
            } else if (isset($request->supplier->originalObject)) {
                $supplier_user_id = $request->supplier->originalObject->user_id;
                $conditions .= " AND user_id = " . $supplier_user_id;
            }
        }

        if ($this->user->level == User::SUPPLIER) {
            $conditions .= " AND user_id = " . $this->user->id;
        }


        if (isset($request->from_date)) {
            $conditions .= " AND DATE(created_on) >= '$request->from_date'";
        }
        if (isset($request->to_date)) {
            $conditions .= " AND DATE(created_on) <= '$request->to_date'";
        }
        if (isset($request->status)) {
            if ($request->status > Quote::VIEWED) {
                $conditions .= " AND status = '$request->status'";
            }
        }

        $params = array(
            $conditions,
            "order" => "created_on DESC, status ASC",
        );
        $params['group'] = array('job_id', 'job_type');
        #$this->view->params = $params; return;
        $quotes = Quote::find($params);
        $results = array();
        foreach($quotes as $quote) {
            $q = $quote->toArray();
            $q['removal'] = $quote->getRemoval();
            $q['storage'] = $quote->getStorage();
            $q['invoiced'] = Quote::count("job_type = '$quote->job_type' AND job_id = $quote->job_id
                AND invoice_id is NOT NULL");
            $results[] = $q;
        }
        $this->view->results = $results;

    }

    public function addAllSuppliersAction()
    {
        $request = $this->request->getJsonRawBody();
        $quote_id = $request->quote_id;
        $free = 0;
        if (isset($request->free) && $request->free == 'YES')
        {
            $free = 1;
        }

        $quote = Quote::findFirst($quote_id);
        $suppliers = Supplier::find("status = " . Supplier::APPROVED);

        $errors = array();
        $new_suppliers = array();
        foreach($suppliers as $supplier)
        {
            # Check if the quote has been sent to this supplier
            $conditions = "job_type = :job_type: AND job_id = :job_id: AND user_id = :user_id:";
            $parameters = array(
                'job_type' => $quote->job_type,
                'job_id' => $quote->job_id,
                'user_id' => $supplier->user_id
            );
            $supplier_quote = Quote::findFirst(array(
                $conditions,
                "bind" => $parameters
            ));
            if (!$supplier_quote)
            {
                $new_quote = new Quote();
                $new_quote->job_type = $quote->job_type;
                $new_quote->job_id = $quote->job_id;
                $new_quote->user_id = $supplier->user_id;
                $new_quote->status = Quote::FRESH;
                $new_quote->free = ($supplier->free) ? 1 : $free;
                $new_quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($new_quote->save() == false)
                {
                    foreach($new_quote->getMessages() as $message) {
                        $errors[] = (string) $message;
                    }
                }

                $new_suppliers[] = $supplier->toArray(); # For display purpose
            }
        }

        # Now delete the quote record if user_id = 0
        if ($quote->user_id == 0)
        {
            $quote->delete();
        }

        if (count($errors) > 0)
        {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
        else
        {
            $this->response->setStatusCode(200, 'OK');
            $this->view->suppliers = $new_suppliers;
        }
    }

    public function addSupplierAction()
    {
        $request = $this->request->getJsonRawBody();
        $quote_id = $request->quote_id;
        $supplier_id = $request->supplier_id;
        $free = 0;
        if (isset($request->free) && $request->free == 'YES')
        {
            $free = 1;
        }

        $quote = Quote::findFirst($quote_id);
        $supplier = Supplier::findFirst($supplier_id);

        $errors = array();

        # First check if quote has not been sent to any supplier
        if ($quote->user_id == 0) {
            # Update user_id for this quote
            $quote->user_id = $supplier->user_id;
            $quote->free = ($supplier->free) ? 1 : $free;

            if ($quote->save() == false)
            {
                foreach($quote->getMessages() as $message) {
                    $errors[] = (string) $message;
                }
            }
        }
        else # Quote has been sent to supplier
        {
            # Now make sure this supplier has not receive this quote
            $conditions = "job_type = :job_type: AND job_id = :job_id: AND user_id = :user_id:";
            $parameters = array(
                'job_type' => $quote->job_type,
                'job_id' => $quote->job_id,
                'user_id' => $supplier->user_id
            );
            $supplier_quote = Quote::findFirst(array(
                $conditions,
                "bind" => $parameters
            ));
            if ($supplier_quote)
            {
                $errors[] = "This supplier has already received this quote";
            }
            else
            {
                $new_quote = new Quote();
                $new_quote->job_type = $quote->job_type;
                $new_quote->job_id = $quote->job_id;
                $new_quote->user_id = $supplier->user_id;
                $new_quote->status = Quote::FRESH;
                $new_quote->free = ($supplier->free) ? 1 : $free;
                $new_quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($new_quote->save() == false)
                {
                    foreach($new_quote->getMessages() as $message) {
                        $errors[] = (string) $message;
                    }
                } else {
                    # Send new quote notification to supplier
                    $emails = array();
                    if ($supplier->email_quote_cc) {
                        $emails = array_map('trim', explode(',', $supplier->email_quote_cc));
                    }
                    $emails = array_filter($emails);

                    if ($new_quote->job_type == 'removal') {
                        $removal = Removal::findFirst($new_quote->job_id);
                        if ($removal->is_international == 'no') {
                            // $from = Postcodes::findFirstByPostcode($removal->from_postcode);
                            // $to = Postcodes::findFirstByPostcode($removal->to_postcode);
                            $from = Postcodes::findFirst(array(
                                "postcode = :postcode: AND lat = :lat: AND lon = :lon:",
                                "bind" => array(
                                    "postcode" => $removal->from_postcode,
                                    "lat" => $removal->from_lat,
                                    "lon" => $removal->from_lon
                                )
                            ));
                            $to = Postcodes::findFirst(array(
                                "postcode = :postcode: AND lat = :lat: AND lon = :lon:",
                                "bind" => array(
                                    "postcode" => $removal->to_postcode,
                                    "lat" => $removal->to_lat,
                                    "lon" => $removal->to_lon
                                )
                            ));

                        } else {
                            $from = $removal->from_country;
                            $to = $removal->to_country;
                        }
                        $this->mail->send(
                            $supplier->email,
                            'New Removalist Job',
                            'new_removal',
                            array(
                                'removal' => $removal,
                                'from' => $from,
                                'to' => $to
                            ),
                            $emails
                        );
                    }
                    if ($new_quote->job_type == 'storage') {
                        $storage = Storage::findFirst($new_quote->job_id);
                        $pickup = Postcodes::findFirstByPostcode($storage->pickup_postcode);
                        $this->mail->send(
                            $supplier->email,
                            'New Removalist Job',
                            'new_storage',
                            array(
                                'storage' => $storage,
                                'pickup' => $pickup
                            ),
                            $emails
                        );
                    }

                }
            }
        }

        if (count($errors) > 0)
        {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
        else
        {
            $this->response->setStatusCode(200, 'OK');
            $this->view->supplier = $supplier->toArray();
        }

    }

    public function deleteQuoteAction($id)
    {
        $quote = Quote::findFirst($id);

        if ($quote->job_type == Quote::REMOVAL)
        {
            $removal = Removal::findFirst($quote->job_id);
            $removal->delete();
        }
        else
        {
            $storage = Storage::findFirst($quote->job_id);
            $storage->delete();
        }
    }

	public function getDuplicateRemovalQuotesAction()
	{
		$conditions = "is_duplicate = :is_duplicate: AND duplicate_status = :duplicate_status: GROUP BY parent_id ORDER BY created_on DESC";
		$parameters = array(
			'is_duplicate' => 1,
			'duplicate_status' => 0
		);
		$removals = Removal::find(array(
								  $conditions,
								  "bind" => $parameters
							  ))->toArray();

		// build removal such that the list is in this format
		// Parent row - Distributed or not
		//	- child row
		//	- child row

		$duplicates = array();
		$duplicate_removal_count = 0;
		foreach($removals as $key => $removal){
			# get parent
			$parent_id = $removal['parent_id'];
			$parent = Removal::findFirst(array('id = ' . $parent_id))->toArray();

			# check if parent has been distributed
			$conditions = "job_id = :job_id: AND user_id != 0";
            $parameters = array(
                'job_id' => $parent_id
            );
			$suppliers = Quote::find(array(
								  $conditions,
								  "bind" => $parameters
							  ))->toArray();


			if($suppliers){
				# get duplicates
				$conditions = "is_duplicate = :is_duplicate: AND duplicate_status = :duplicate_status: AND parent_id = :parent_id:";
				$parameters = array(
					'is_duplicate' => 1,
					'duplicate_status' => 0,
					'parent_id' => $parent_id
				);
				$childrens = Removal::find(array(
								$conditions,
								"bind" => $parameters
							))->toArray();

				foreach($childrens as $children_key => $children){
					$childrens[$children_key]['suppliers'] = array();
				}

				$duplicates[$key] = $parent;
				$duplicates[$key]['duplicates'] = $childrens;


				foreach($suppliers as $suplier){
					$duplicates[$key]['suppliers'][] = Supplier::findFirst(array('user_id = ' . $suplier['user_id']));
				}
				#$duplicates[$key]['suppliers'] = $suppliers;

				# count removal duplicates
				$duplicate_removal_count += count($childrens);
			}



		}
		$q['removal'] = $duplicates;
		$q['storage'] = array();
		$q['removal_duplicate_count'] = $duplicate_removal_count;
		$results[] = $q;
        $this->view->results = $results;
	}

	public function deleteDuplicateRemovalQuoteAction($id)
	{
		$duplicate = Removal::findFirst($id);
		if($duplicate){
			$duplicate->duplicate_status = -1;
		}
		$duplicate->save();
		$this->view->results = $duplicate;

	}

	public function reSendDuplicateRemovalQuoteAction($id)
	{
		$duplicate = Removal::findFirst($id);
		if($duplicate){
			$duplicate->duplicate_status = 1;
			$parent_id = $duplicate->parent_id;
			$job_id = $duplicate->id;
		}
		$duplicate->save();

		$errors = array();
		if($parent_id){
			# insert quote
			$conditions = "job_id = :job_id:";
			$parameters = array(
				'job_id' => $parent_id,
			);
			$prev_suppliers = Quote::find(array(
								  $conditions,
								  "bind" => $parameters
							  ))->toArray();
			if($prev_suppliers){
				foreach($prev_suppliers as $supplier){
					$new_quote = new Quote();
					$new_quote->job_type = 'removal';
					$new_quote->job_id = $job_id;
					$new_quote->user_id = $supplier['user_id'];
					$new_quote->status = 0;
					$new_quote->free = 1;
					$new_quote->created_on = new Phalcon\Db\RawValue('now()');
					if ($new_quote->save() == false)
					{
						foreach($new_quote->getMessages() as $message) {
							$errors[] = (string) $message;
						}
					}
				}
			}

		}
		$this->view->results = $out;
		if (count($errors) > 0)
        {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
        else
        {
            $this->response->setStatusCode(200, 'OK');
            $this->view->message = 'Successfully Re Distributed';
        }
	}

	public function addSupplierToDuplicateAction()
    {
        $request = $this->request->getJsonRawBody();
        $removal_id = $request->quote_id;
        $supplier_id = $request->supplier_id;
        $free = 0;
        if (isset($request->free) && $request->free == 'YES')
        {
            $free = 1;
        }

        $removal = Removal::findFirst($removal_id);
        $supplier = Supplier::findFirst($supplier_id);

        $errors = array();
		$new_quote = new Quote();
		$new_quote->job_type = 'removal';
		$new_quote->job_id = $removal_id;
		$new_quote->user_id = $supplier->user_id;
		$new_quote->status = 0;
		$new_quote->free = $free;
		$new_quote->created_on = new Phalcon\Db\RawValue('now()');
		if ($new_quote->save() == false)
		{
			foreach($new_quote->getMessages() as $message) {
				$errors[] = (string) $message;
			}
		}
		else
		{
			$removal->duplicate_status = 1;
			$removal->save();
		}


        if (count($errors) > 0)
        {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
        else
        {
            $this->response->setStatusCode(200, 'OK');
            $this->view->supplier = $supplier->toArray();
        }

    }
}

