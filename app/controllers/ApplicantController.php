<?php

class ApplicantController extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->supplier = Supplier::findFirstByUserId($auth['id']);
            $this->user = User::findFirst($auth['id']);
        }
        $this->view->baseUrl = $this->url->get('');
    }

    public function indexAction()
    {
        return $this->response->redirect('login');
    }

    public function registerAction($supplierId = null, $activation_key = null)
    {
        $supplier = Supplier::findFirst("
            id='$supplierId'
            AND status='" . Supplier::ACTIVATED . "'
            AND activation_key='$activation_key'");

        if (!$supplier) {
            return $this->response->redirect('login');
        }
        $this->tag->setTitle('Register');

        $this->view->supplier = $supplier;

        $form = new RegisterForm();

        if ($this->request->isPost()) {
            $username = $this->request->getPost('username', 'alphanum');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if ($password != $repeatPassword) {
                $this->flash->error('Confirm password does not match');
            } else {
                $user = new User();
                $user->status = User::PENDING;
                $user->username = $username;
                $user->password = md5($password);
                $user->level = User::SUPPLIER;
                if ($user->save()) {
                    $supplier->activation_key = null;
                    $supplier->user_id = $user->id;
                    $supplier->save();
                    $this->session->set('auth', array(
                        'id' => $user->id,
                        'username' => $user->username,
                        'status' => $user->status,
                        'level' => $user->level
                    ));
                    $this->response->redirect('applicant/profile');
                }
                foreach($user->getMessages() as $message) {
                    $this->flash->error($message);
                }

            }
        }
        $this->view->form = $form;
    }

    public function profileAction()
    {
        $this->tag->setTitle('Profile');

        if ($this->request->isPost()) {
            # Update supplier data
            foreach($this->request->getPost() as $key => $value) {
                $this->supplier->$key = $value;
                $this->supplier->save();
                $this->response->redirect('applicant/location/local');
            }
        }

        $form = new ProfileForm($this->supplier, array('edit' => true));
        $this->view->form = $form;
    }

    public function locationAction($zoneType='local')
    {
        $this->tag->setTitle(ucwords($zoneType) . ' Zones');
        if ($zoneType == 'country') {
            $this->view->local_zones = ZoneLocal::find("user_id = " . $this->user->id);
        }
        $this->view->zoneType = $zoneType;
        $this->view->goNext = true;

    }

    public function filterAction()
    {
        $this->tag->setTitle('Quote Filter');

        if ($this->request->isPost())
        {
            foreach($this->request->getPost() as $name => $value)
            {
                $filter = SupplierFilter::findFirst("user_id = " . $this->user->id
                        . " AND name = '$name'");
                if (!$filter) {
                    $filter = new SupplierFilter();
                    $filter->user_id = $this->user->id;
                    $filter->name = $name;
                    $filter->value = $value;
                    $filter->created_on = new Phalcon\Db\RawValue('now()');
                }
                if ($filter->value != $value) # Updated
                {
                    $filter->value = $value;
                    $filter->updated_on = new Phalcon\Db\RawValue('now()');
                }
                $filter->save();
                $this->response->redirect('applicant/payment');
            }
        }

        $filters = SupplierFilter::find("user_id = " . $this->user->id);
        foreach($filters as $filter)
        {
            $this->tag->setDefault($filter->name, $filter->value);
        }
    }

    public function paymentAction()
    {
        $this->tag->setTitle('Payment Information');
    }


}

