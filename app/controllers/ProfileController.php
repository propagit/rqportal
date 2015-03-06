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

    public function passwordAction()
    {
        $this->tag->setTitle('Update password');

        if ($this->request->isPost()) {
            $newPassword = $this->request->getPost('newPassword');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if (!$newPassword) {
                $this->flash->error('Please enter your new password');
            }
            else if ($newPassword != $repeatPassword) {
                $this->flash->error('Confirm password does not match');
            } else {
                $this->user->password = md5($newPassword);
                if ($this->user->save()) {
                    $this->flash->success('Password has been updated successfully!');
                }
            }
        }


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
        $this->tag->setTitle('Payment Details');
        $this->view->supplier = $this->supplier;


        if ($this->request->isPost())
        {
            if (!$this->request->getPost('title') || !$this->request->getPost('firstname')
                || !$this->request->getPost('lastname') || !$this->request->getPost('ccnumber')
                || !$this->request->getPost('ccexpmonth') || !$this->request->getPost('ccexpyear')
                || !$this->request->getPost('cvn')) {
                $this->flash->error('Please enter all fields');
                return;
            }

            $eway_customer = array(
                'CustomerRef' => $this->supplier->user_id,
                'Title' => $this->request->getPost('title'),
                'FirstName' => $this->request->getPost('firstname'),
                'LastName' => $this->request->getPost('lastname'),
                'Email' => $this->supplier->email,
                'Address' => $this->supplier->address,
                'Suburb' => $this->supplier->suburb,
                'State' => $this->supplier->state,
                'PostCode' => $this->supplier->postcode,
                'Phone' => $this->supplier->phone,
                'Mobile' => '',
                'Fax' => '',
                'Country' => 'au',
                'Company' => $this->supplier->company,
                'JobDesc' => '',
                'URL' => $this->supplier->website,
                'Comments' => $this->supplier->about,
                'CCNameOnCard' => $this->request->getPost('firstname') . ' ' . $this->request->getPost('lastname'),
                'CCNumber' => $this->request->getPost('ccnumber'),
                'CCExpiryMonth' => $this->request->getPost('ccexpmonth'),
                'CCExpiryYear' => $this->request->getPost('ccexpyear')
            );

            if (!$this->supplier->eway_customer_id)
            {
                # Create customer
                try {
                    $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
                    $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
                    $client->__setSoapHeaders(array($header));
                    $result = $client->CreateCustomer($eway_customer);
                    $this->supplier->eway_customer_id = $result->CreateCustomerResult;
                    $this->supplier->cvn = $this->request->getPost('cvn');
                    $this->supplier->save();
                    $this->flash->success('New payment detail has been added successfully!');
                } catch(Exception $e) {
                    $this->flash->error($e->getMessage());
                }
            }
            else
            {
                # Update customer
                $eway_customer['managedCustomerID'] = $this->supplier->eway_customer_id;
                try {
                    $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
                    $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
                    $client->__setSoapHeaders(array($header));
                    $result = $client->UpdateCustomer($eway_customer);
                    if ($result->UpdateCustomerResult) {
                        $this->flash->success('Payment detail has been update successfully!');
                    }
                    $this->supplier->cvn = $this->request->getPost('cvn');
                    $this->supplier->save();
                } catch(Exception $e) {
                    $this->flash->error($e->getMessage());
                }
            }
        }


        $eway_customer = null;
        if ($this->supplier->eway_customer_id)
        {
            try {
                $client = new SoapClient($this->config->eway->endpoint, array('trace' => 1));
                $header = new SoapHeader($this->config->eway->namespace, 'eWAYHeader', $this->config->eway->headers);
                $client->__setSoapHeaders(array($header));
                $result = $client->QueryCustomer(array(
                    'managedCustomerID' => $this->supplier->eway_customer_id
                ));
                $eway_customer = $result->QueryCustomerResult;
            } catch(Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
        $this->view->eway_customer = $eway_customer;
    }

}

