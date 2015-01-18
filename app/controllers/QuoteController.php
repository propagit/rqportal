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
                $r = $removal->toArray();
                $r['path'] = $removal->drawPath(); # Draw Path
                $r['from_marker'] = $removal->drawFromMarker(); # Draw From marker
                $r['to_marker'] = $removal->drawToMarker(); # Draw To marker
                $r['quote'] = $quote->toArray(); # Inject quote
                $removals[] = $r;
            } else {
                $storage = Storage::findFirst($quote->job_id);
                $s = $storage->toArray();
                $s['pickup_marker'] = $storage->drawPickupMarker(); # Draw Pickup marker
                $s['quote'] = $quote->toArray(); # Inject quote
                $storages[] = $s;
            }
        }
        $this->response->setContent(json_encode(array(
            'removals' => $removals,
            'storages' => $storages
        )));
        return $this->response;
    }

    public function ajaxGetAllAction()
    {
        $this->view->disable();
        $quotes = Quote::find(array(
            "user_id = " . $this->user->id,
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

        $quote->status = $payload->status;
        $quote->save();
        $q = $quote->toArray();
        $q['removal'] = $quote->getRemoval();
        $q['storage'] = $quote->getStorage();
        $this->response->setContent(json_encode($q));
        return $this->response;
    }

}

