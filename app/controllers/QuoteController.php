<?php

class QuoteController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->setTitle('Search Quote');
    }

    public function ajaxGetAllAction()
    {
        $this->view->disable();

        $conditions = "";
        if ($this->user->level == User::SUPPLIER) {
            $conditions = "user_id = " . $this->user->id;
        }
        $params = array(
            $conditions,
            "order" => "created_on DESC, status DESC",
        );
        if ($this->user->level == User::ADMIN) {
            $params['group'] = array('job_id', 'job_type');
        }

        $quotes = Quote::find($params);
        $results = array();
        foreach($quotes as $quote) {
            $q = $quote->toArray();
            $q['removal'] = $quote->getRemoval();
            $q['storage'] = $quote->getStorage();
            $results[] = $q;
        }
        $this->response->setContent(json_encode($results));
        return $this->response;
    }

    public function ajaxGetOneAction($id)
    {
        $this->view->disable();
        $quote = Quote::findFirst($id);
        $q = $quote->toArray();
        $q['removal'] = $quote->getRemoval();
        $q['storage'] = $quote->getStorage();
        $this->response->setContent(json_encode($q));
        return $this->response;
    }

    public function ajaxUpdateAction($id)
    {
        $this->view->disable();
        $payload = $this->request->getJsonRawBody();
        $quote = Quote::findFirst($id);

        if ($payload->status > Quote::VIEWED) {
            $quote->status = $payload->status;
        } else if ($quote->status == Quote::FRESH) {
            $quote->status = Quote::VIEWED;
        }
        $quote->save();
        $q = $quote->toArray();
        $q['removal'] = $quote->getRemoval();
        $q['storage'] = $quote->getStorage();
        $q['suppliers'] = $quote->getSuppliers();
        $this->response->setContent(json_encode($q));
        return $this->response;
    }

}

