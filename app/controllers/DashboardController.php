<?php

class DashboardController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Dashboard');
        parent::initialize();
        $this->view->parent = 'dashboard';

        $auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('login');
        } else if ($auth['status'] == User::PENDING) {
            $this->response->redirect('applicant');
        }
    }

    public function indexAction()
    {
        $this->tag->setTitle('Dashboard');

        $unpaid_invoice = Invoice::find("status = " . Invoice::UNPAID);
        $this->view->unpaid_invoice = count($unpaid_invoice);

        $outstanding_quote = Quote::find("invoice_id IS NULL AND user_id > 0");
        $this->view->outstanding_quote = count($outstanding_quote);

        $unallocated_quote = Quote::find("user_id = 0");
        $this->view->unallocated_quote = count($unallocated_quote);

        $applied_supplier = Supplier::find("status = " . Supplier::APPLIED);
        $this->view->applied_supplier = count($applied_supplier);

        $incompleted_supplier = Supplier::find("status = " . Supplier::ACTIVATED);
        $this->view->incompleted_supplier = count($incompleted_supplier);
    }

}

