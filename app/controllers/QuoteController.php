<?php

class QuoteController extends ControllerBase
{
    public function initialize()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->user = User::findFirstById($auth['id']);
        }
        parent::initialize();
    }

    public function indexAction()
    {

    }

    public function searchAction()
    {
        $this->tag->setTitle('Search Quote');
        $this->view->quotes = Quote::find();
    }

    public function ajaxGetAllAction()
    {
        $this->view->disable();

        $conditions = "";
        if ($this->user->level == User::SUPPLIER) {
            $conditions = "user_id = " . $this->user->id;
        }

        $quotes = Quote::find(array(
            $conditions,
            "order" => "created_on DESC",
        ));
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

        if ($payload->status > 1) {
            $quote->status = $payload->status;
        } else if ($quote->status == 0) {
            $quote->status = 1;
        }
        $quote->save();
        $q = $quote->toArray();
        $q['removal'] = $quote->getRemoval();
        $q['storage'] = $quote->getStorage();
        $this->response->setContent(json_encode($q));
        return $this->response;
    }

}

