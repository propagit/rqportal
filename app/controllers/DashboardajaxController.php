<?php

class DashboardajaxController extends ControllerAjax
{

    public function getStatsAction()
    {
        $request = $this->request->getJsonRawBody();

        $time = 'month'; # Default
        if (isset($request->time))
        {
            $time = $request->time;
        }

        $condition = '';
        if ($time == 'month')
        {
            $condition = "created_on LIKE '" . date('Y-m') . "%'";
        }

        $income = Invoice::sum(array(
            "column" => "amount",
            "conditions" => "status = " . Invoice::PAID .
                " AND paid_on LIKE '" . date('Y-m') . "%'" .
                ($condition ? " AND $condition" : "")
        ));
        if (!$income) { $income = 0; }
        $this->view->income = $income;

        $this->view->total_invoice = Invoice::count($condition);
        $this->view->unpaid_invoice = Invoice::count("status = " . Invoice::UNPAID .
            ($condition ? " AND $condition" : ""));

        $this->view->total_quotes = Removal::count($condition) + Storage::count($condition);
        $this->view->unallocated_quote = Quote::count("user_id = 0" .
            ($condition ? " AND $condition" : ""));

        $this->view->total_suppliers = Supplier::count("status > " . Supplier::APPLIED .
            ($condition ? " AND $condition" : ""));
        $this->view->incompleted_supplier = Supplier::count("status > 0 AND status <= " . Supplier::ACTIVATED .
            ($condition ? " AND $condition" : ""));
    }

    public function getSalesAction()
    {
        $series = array("Revenue", "Forecast");
        $labels = array();
        $billed = array();
        $predicted = array();

        for($i=12; $i >= 0; $i--)
        {
            $month = strtotime(date('Y-m') . "-$i month");
            $labels[] = date('M Y', $month);

            $sales = Invoice::sum(array(
                "column" => "amount",
                "conditions" => "status = " . Invoice::PAID .
                    " AND paid_on LIKE '" . date('Y-m', $month) . "%'"
            ));
            $billed[] = ($sales) ? $sales : 0;

            $unpaid = Invoice::sum(array(
                "column" => "amount",
                "conditions" => "status = " . Invoice::UNPAID .
                    " AND created_on LIKE '" . date('Y-m', $month) . "%'"
            ));

            $unpaid = ($unpaid) ? $unpaid : 0;

            $quotes = Quote::count("invoice_id is NULL AND free = 0 AND created_on LIKE '" . date('Y-m', $month) . "%'");
            $predicted[] = $quotes * 10 + $sales + $unpaid;
        }

        $this->view->series = $series;
        $this->view->labels = $labels;
        $this->view->data = array($billed, $predicted);
    }

}

