<?php

class QuoteAjaxController extends ControllerAjax
{

    public function searchAction()
    {
        $request = $this->request->getJsonRawBody();

        $conditions = "1=1";
        if (isset($request->allocated)) {
            if ($request->allocated == 'not_allocated') {
                $conditions .= "AND user_id = 0";
            } else if (isset($request->supplier->originalObject)) {
                $supplier_user_id = $request->supplier->originalObject->user_id;
                $conditions .= "AND user_id = " . $supplier_user_id;
            }
        }
        if (isset($request->from_date)) {
            $conditions .= " AND DATE(created_on) >= '$request->from_date'";
        }
        if (isset($request->to_date)) {
            $conditions .= " AND DATE(created_on) <= '$request->to_date'";
        }
        if (isset($request->status)) {
            $conditions .= " AND status = '$request->status'";
        }

        $params = array(
            $conditions,
            "order" => "created_on DESC, status DESC",
        );
        $params['group'] = array('job_id', 'job_type');

        $quotes = Quote::find($params);
        $results = array();
        foreach($quotes as $quote) {
            $q = $quote->toArray();
            $q['removal'] = $quote->getRemoval();
            $q['storage'] = $quote->getStorage();
            $results[] = $q;
        }
        $this->view->results = $results;

    }

}
