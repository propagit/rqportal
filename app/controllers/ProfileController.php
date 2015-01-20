<?php

class ProfileController extends ControllerBase
{

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->supplier = Supplier::findFirstByUserId($auth['id']);
            $this->user = User::findFirstById($auth['id']);
        }
        parent::initialize();
    }

    public function indexAction()
    {

    }

    public function companyAction()
    {
        $this->tag->setTitle('Company Profile');

        if ($this->request->isPost())
        {
            foreach($this->request->getPost() as $key => $value) {
                $this->supplier->$key = $value;
            }
            if ($this->supplier->save()) {
                $this->flash->success('Your company profile has been updated successfully!');
            } else {
                foreach($this->supplier->getMessages() as $message)
                {
                    $this->flash->error((string) $message);
                }
            }
        }
        $form = new ProfileForm($this->supplier, array('edit' => true));
        $this->view->form = $form;
    }

    public function locationAction($zoneType='local')
    {
        $this->tag->setTitle('Work Locations - ' . ucwords($zoneType) . ' Zones');
        if ($zoneType == 'country') {
            $this->view->local_zones = ZoneLocal::find("user_id = " . $this->user->id);
        }
        $this->view->goNext = false;
        $this->view->zoneType = $zoneType;
    }

    public function paymentAction()
    {

    }

}

