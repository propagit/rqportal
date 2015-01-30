<?php

class DashboardController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Dashboard');
        parent::initialize();
        $this->view->parent = 'dashboard';

        $auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('login');
        } else if ($auth['status'] == User::PENDING) {
            $this->response->redirect('applicant');
        }
    }

    public function indexAction()
    {
        $this->tag->setTitle('Dashboard');
    }

    public function testAction()
    {
        $this->mail->send(
            array('nam@propagate.com.au' => 'Nam Nguyen'),
                'Account Activation',
                'activation',
                array('name' => 'Nam',
                    'activationUrl' => '/applicant/register')
        );
    }
}

