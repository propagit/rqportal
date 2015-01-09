<?php

class LogoutController extends ControllerBase
{

    public function indexAction()
    {
        # Destroy the whole session
        $this->session->destroy();
        return $this->response->redirect('');
    }

}

