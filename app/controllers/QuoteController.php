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

    public function ajaxGetQuotesAction()
    {
        $this->view->disable();
        $quotes = Quote::find(array(
            "user_id = " . $this->user->id,
            "order" => "created_on DESC",
        ));
        $removals = array();
        $storages = array();
        foreach($quotes as $quote) {
            if ($quote->job_type == Quote::REMOVAL) {
                $removal = Removal::findFirst($quote->job_id);
                $removals[] = $removal->toJson();
            } else {
                $storage = Storage::findFirst($quote->job_id);
                $storages[] = $storage->toJson();
            }
        }
        $this->response->setContent(json_encode(array(
            'removals' => $removals,
            'storages' => $storages
        )));
        return $this->response;
    }

}

