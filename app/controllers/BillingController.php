<?php

class BillingController extends ControllerBase
{

    public function manualProcessAction()
    {
        $user = User::findFirst(85);
        $r = $user->emailInvoice(754);
    }

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
		$auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('login');
        } else if ($auth['status'] == User::PENDING) {
            $this->response->redirect('applicant');
        } else if ($auth['level'] == User::SUPPLIER) {
            $this->response->redirect('quote');
        }
        $this->tag->setTitle('Create Manual Invoice');
    }

    public function processAction($id = '')
    {
        $this->tag->setTitle('Process Payment...');
        if (!$id) { return; }
        $invoice = Invoice::findFirst($id);
        if (!$invoice) { return; }

        $result = $invoice->process();
        if ($result['success']) {
            $this->flash->success('Payment transaction approved');
        } else {
            $this->flash->error($result['msg']);
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

