<?php

class BillingController extends ControllerBase
{

    public function invoiceAction()
    {
        $this->tag->setTitle('Search Invoices');

        $this->view->id = $this->request->get('id');
        $this->view->query = $this->request->get('q');
    }


    public function quoteAction()
    {
        $this->tag->setTitle('Outstanding Quotes');
    }

    public function createAction()
    {
        $this->tag->setTitle('Create Manual Invoice');
    }

    public function processAction($id = '')
    {
        $this->tag->setTitle('Process Payment...');
        if (!$id) { return; }
        $invoice = Invoice::findFirst($id);
        if (!$invoice) { return; }
        $supplier = Supplier::findFirstByUserId($invoice->user_id);
        if (!$supplier) { return; }

        if (!$supplier->eway_customer_id)
        {
            $this->flash->error('Supplier has no credit card information');
        }
        else
        {
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
                    $this->flash->success('Payment transaction approved');
                } else {
					# payment failed de activate this account
						$supplier = Supplier::findFirst($invoice->user_id);
						if ($supplier->user_id)
						{
							$user = User::findFirst($supplier->user_id);
							$user->status = User::INACTIVED;
							$user->save();
						}
                    $this->flash->error($invoice->eway_trxn_msg);
                }
                $invoice->save();
            } catch(Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $this->response->redirect('billing/invoice');
    }

    public function downloadAction($id)
    {
        $this->_generatePdf($id);
        return $this->response->redirect('public/files/invoice' . $id . '.pdf');
    }

    private function _generatePdf($id)
    {
        $file = __DIR__ . '/../../public/files/invoice' . $id . '.pdf';
        if (file_exists($file)){
			 unlink($file);
		}
	
            $this->view->disable();
            $data['invoice'] = Invoice::findFirst($id)->toArray();
            $html = $this->view->getRender('billing', 'invoice_pdf', $data);
            $pdf = new mPDF();
            $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
            $pdf->WriteHTML($stylesheet,1);
            $pdf->WriteHTML($html, 2);
            $pdf->Output($file, "F");

    }
}

