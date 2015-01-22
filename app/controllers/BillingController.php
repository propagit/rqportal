<?php

class BillingController extends ControllerBase
{

    public function invoiceAction()
    {
        $this->tag->setTitle('Search Invoices');
    }


    public function quoteAction()
    {
        $this->tag->setTitle('Outstanding Quotes');
    }
}

