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

    public function downloadAction($id)
    {
        $this->_generatePdf($id);
        return $this->response->redirect('public/files/invoice' . $id . '.pdf');
    }

    private function _generatePdf($id)
    {
        $file = __DIR__ . '/../../public/files/invoice' . $id . '.pdf';
        if (!file_exists($file))
        {
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
}

