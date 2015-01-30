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
        $this->view->disable();
        $data['invoice'] = Invoice::findFirst($id)->toArray();
        $html = $this->view->getRender('billing', 'invoice_pdf', $data);
        $pdf = new mPDF();
        $stylesheet = file_get_contents(__DIR__ . '/../../public/css/app.min.css');
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output("invoice$id.pdf", "I");
        $this->response->setHeader("Content-Type", "application/pdf");
        $this->response->setHeader("Content-Disposition", 'attachment; filename="invoice' . $id . '.pdf"');


    }

    public function generatePdfAction()
    {
        $this->view->disable();

        $html = 'just a test';
        $pdf = new mPDF();
        $pdf->WriteHTML($html, 2);

        $pdf->Output("invoice.pdf", "I");
        $this->response->setHeader("Content-Type", "application/pdf");
        $this->response->setHeader("Content-Disposition", 'attachment; filename="invoice.pdf"');
        #$pdf->Output(__DIR__ . "/../../public/files/Test.pdf", "F");

    }
}

