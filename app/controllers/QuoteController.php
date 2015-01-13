<?php

class QuoteController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function searchAction()
    {
        $this->tag->setTitle('Search Quote');
        $this->view->quotes = Quote::find();
    }

    public function ajaxGetAction()
    {
        $this->view->disable();
        $quotes = Quote::find();
        $results = array();
        foreach($quotes as $quote) {
            $results[] = $quote->toJson();
        }
        $this->response->setContent(json_encode(array(
            'quotes' => $results
        )));
        return $this->response;
    }

}

