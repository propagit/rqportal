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
            "order" => "created_on ASC, status ASC",
        );
        $params['group'] = array('job_id', 'job_type');
        #$this->view->params = $params; return;
        $quotes = Quote::find($params);
        $results = array();
        foreach($quotes as $quote) {
            $q = $quote->toArray();
            $q['removal'] = $quote->getRemoval();
            $q['storage'] = $quote->getStorage();
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
                $new_quote->free = $free;
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
            $quote->free = $free;
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
                $new_quote->free = $free;
                $new_quote->created_on = new Phalcon\Db\RawValue('now()');
                if ($new_quote->save() == false)
                {
                    foreach($new_quote->getMessages() as $message) {
                        $errors[] = (string) $message;
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

}

