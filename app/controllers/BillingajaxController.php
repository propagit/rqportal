<?php

class BillingajaxController extends ControllerAjax
{

    public function searchAction($keyword)
    {
        $query = "SELECT i.id, s.name, s.business FROM Invoice i
                        LEFT JOIN Supplier s ON s.user_id = i.user_id
                    WHERE (i.id LIKE '%$keyword%'
                        OR s.name LIKE '%$keyword%'
                        OR s.business LIKE '%$keyword%')";
        if ($this->user->level == User::SUPPLIER)
        {
            $query .= " AND i.user_id = " . $this->user->id;
        }
        $invoices = $this->modelsManager->executeQuery($query);
        $results = array();
        foreach($invoices as $invoice)
        {
            $results[] = array(
                'id' => $invoice->id,
                'name' => 'Invoice #' . $invoice->id,
                'supplier' => $invoice->name . ' (' . $invoice->business . ')'
            );
        }
        $this->view->invoices = $results;

    }

    public function getInvoicesAction()
    {
        $invoices = Invoice::find(array(
            "order" => "id DESC"
        ));
        $results = array();
        foreach($invoices as $invoice) {
            $results[] = $invoice->toArray();
        }
        $this->view->invoices = $results;

    }

    public function searchInvoicesAction()
    {
        $request = $this->request->getJsonRawBody();

        $conditions = "1=1";
        $parameters = array();
        if ($this->user->level == User::SUPPLIER)
        {
            $conditions .= " AND user_id = :user_id:";
            $parameters['user_id'] = $this->user->id;
        }

        $invoices = Invoice::find(array(
            $conditions,
            "bind" => $parameters,
            "order" => "id DESC"
        ));
        $results = array();
        foreach($invoices as $invoice)
        {
            $results[] = $invoice->toArray();
        }
        $this->view->invoices = $results;
    }

    public function processInvoiceAction()
    {
        $request = $this->request->getJsonRawBody();

        if (!isset($request->id)) { return; }
        $invoice = Invoice::findFirst($request->id);
        if (!$invoice) { return; }
        $invoice->status = Invoice::PAID;
        $invoice->paid_on = date('Y-m-d H:i:s');
        if ($invoice->save())
        {
            $this->response->setStatusCode(200, 'OK');
            $this->view->invoice = $invoice->toArray();
        }
        else
        {
            $errors = array();
            foreach($invoice->getMessages() as $message)
            {
                $errors[] = (string) $message;
            }
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
    }

    public function getSuppliersAction()
    {
        $quotes = Quote::count(array(
            "invoice_id is NULL AND free = 0 AND user_id > 0",
            "group" => "user_id"
        ));
        $suppliers = array();
        foreach($quotes as $quote) {
            $supplier = Supplier::findFirstByUserId($quote->user_id);
            $supplier = $supplier->toArray();
            $supplier['quotes'] = $quote->rowcount;
            $suppliers[] = $supplier;
        }

        $this->view->suppliers = $suppliers;
    }

    public function getQuotesAction($userId)
    {
        $quotes = Quote::find("user_id = $userId AND free = 0 AND invoice_id is NULL");
        $results = array();
        foreach($quotes as $quote) {
            $q = $quote->toArray();
            if ($q['job_type'] == 'removal') {
                $removal = $quote->getRemoval();
                $q['customer_name'] = $removal['customer_name'];
                $q['postcode'] = $removal['from_postcode'] . ' - ' . $removal['to_postcode'];
            } else {
                $storage = $quote->getStorage();
                $q['customer_name'] = $storage['customer_name'];
                $q['postcode'] = $storage['pickup_postcode'];
            }
            $q['job_type'] = ucwords($q['job_type']);
            $results[] = $q;
        }
        $this->view->quotes = $results;
    }

    public function deleteQuoteAction($quoteId)
    {
        $quote = Quote::findFirst($quoteId);
        if ($quote) {
            $quote->delete();
        }
    }

    public function createInvoiceAction($user_id)
    {
        $this->queue->put(array('create_invoice' => $user_id));
        $this->response->setStatusCode(200, 'OK');
    }

    public function emailInvoiceAction()
    {
        $errors = array();
        #$request = $this->request->getJsonRawBody();
        $request = (object) array(
            'id' => '10023',
            'email' => 'nam@propagate.com.au'
        );
        if (!isset($request->id) || !isset($request->email))
        {
            $errors[] = "Invalid request";
        }
        else if (!$this->mail->valid_email($request->email))
        {
            $errors[] = "Invalid email address";
        }
        else
        {
            $this->queue->put(array('email_invoice' => array(
                'id' => $request->id,
                'email' => $request->email
            )));
        }
        if (count($errors) > 0)
        {
            $this->response->setStatusCode(400, 'ERROR');
            $this->view->message = implode(', ', $errors);
        }
        else
        {
            $this->response->setStatusCode(200, 'OK');
        }


    }

    public function deleteInvoiceAction($id)
    {
        $invoice = Invoice::findFirst($id);
        $invoice->delete();
    }

}

