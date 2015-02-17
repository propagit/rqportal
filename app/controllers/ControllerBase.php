<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected function initialize()
    {
        // $this->tag->prependTitle('RQ Portal');
        $this->view->setTemplateAfter('main');
        $this->view->baseUrl = $this->url->get('');

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->user = User::findFirst($auth['id']);
        }
    }

    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        $action = 'index';
        if (isset($uriParts[1])) {
            $action = $uriParts[1];
        }
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action' => $action,
                'params' => $params
            )
        );
    }


}
