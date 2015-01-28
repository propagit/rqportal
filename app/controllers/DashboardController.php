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

        $this->view->today_income = Invoice::sum(array(
            "column" => "amount",
            "conditions" => "status = " . Invoice::PAID .
                " AND paid_on LIKE '" . date('Y-m') . "%'"
        ));

        $this->view->total_invoice = Invoice::count();
        $this->view->unpaid_invoice = Invoice::count("status = " . Invoice::UNPAID);

        $this->view->total_quotes = Removal::count() + Storage::count();
        $this->view->unallocated_quote = Quote::count("user_id = 0");

        $this->view->total_suppliers = Supplier::count("status > " . Supplier::APPLIED);
        $this->view->incompleted_supplier = Supplier::count("status <= " . Supplier::ACTIVATED);
    }

}

