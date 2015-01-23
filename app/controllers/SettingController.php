<?php

class SettingController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->setTitle('System Configuration');

        $auto_allocate_quote = Setting::findFirstByName(Setting::AUTO_ALLOCATE_QUOTE);
        $supplier_per_quote = Setting::findFirstByName(Setting::SUPPLIER_PER_QUOTE);
        $price_per_quote = Setting::findFirstByName(Setting::PRICE_PER_QUOTE);
        $invoice_threshold = Setting::findFirstByName(Setting::INVOICE_THRESHOLD);

        if ($this->request->isPost())
        {
            $auto_allocate_quote->value = $this->request->getPost('auto_allocate_quote') ? 1 : 0;
            $auto_allocate_quote->save();

            $supplier_per_quote->value = $this->request->getPost('supplier_per_quote');
            $supplier_per_quote->save();

            $price_per_quote->value = $this->request->getPost('price_per_quote');
            $price_per_quote->save();

            $invoice_threshold->value = $this->request->getPost('invoice_threshold');
            $invoice_threshold->save();

            $this->flash->success('Update successfully!');
        }

        $this->view->auto_allocate_quote = $auto_allocate_quote;
        $this->view->supplier_per_quote = $supplier_per_quote;
        $this->view->price_per_quote = $price_per_quote;
        $this->view->invoice_threshold = $invoice_threshold;


    }

}

