<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ControllerAjax extends Controller
{
    public function initialize()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->user = User::findFirst($auth['id']);
        }
        $this->view->baseUrl = $this->url->get('');
    }

    // After route execute event
    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {

        $this->view->disableLevel(array(
            View::LEVEL_ACTION_VIEW => true,
            View::LEVEL_LAYOUT => true,
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_AFTER_TEMPLATE => true,
            View::LEVEL_BEFORE_TEMPLATE => true
        ));
        // $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $data = $this->view->getParamsToView();

        if (is_array($data))
        {
            $data = json_encode($data);
        }

        $this->response->setContent($data);


        //return $this->response->send();
    }
}
