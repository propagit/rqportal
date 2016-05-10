<?php

class SupplierController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->setTitle('Supplier');
        $this->view->parent = 'supplier';

		$auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('login');
        } else if ($auth['status'] == User::PENDING) {
            $this->response->redirect('applicant');
        } else if ($auth['level'] == User::SUPPLIER) {
            $this->response->redirect('quote');
        }
    }

    public function indexAction()
    {
        $conditions = "status >= " . Supplier::APPLIED;
        $this->view->suppliers = Supplier::find(array(
            $conditions
        ));
        $this->view->child = 'search';

        $this->view->query = $this->request->get('q');
    }

    public function activateAction($supplierId)
    {
        $supplier = Supplier::findFirst($supplierId);
        $supplier->status = Supplier::ACTIVATED;
        $supplier->activation_key = md5($supplierId . '4w3s0m3');
        if ($supplier->save() == false) {
            foreach($supplier->getMessages() as $message) {
                #var_dump($message);
            }
        } else {
            $this->mail->send(
                array($supplier->email => $supplier->name),
                    'Account Activation',
                    'activation',
                    array('name' => $supplier->name,
                        'activationUrl' =>
                        '/applicant/register/' . $supplier->id . '/' . $supplier->activation_key)
            );

        }
        return $this->response->redirect('supplier');
    }

    public function rejectAction($supplier_id)
    {
        $supplier = Supplier::findFirst($supplier_id);
        $supplier->status = Supplier::REJECTED;
        $supplier->save();
        return $this->response->redirect('supplier');
    }

    public function loginAction($userId)
    {
        $user = User::findFirst($userId);

        $this->session->set('auth', array(
            'id' => $user->id,
            'username' => $user->username,
            'status' => $user->status,
            'level' => $user->level,
            'is_admin' => true,
            'admin_auth' => $this->session->get('auth')
        ));
        return $this->response->redirect('profile/company');
    }


	#To manually test the distribute quote function
	function testAction()
	{
        # Send new quote notification to supplier
        $supplier = Supplier::findFirst(164);
        $emails = array();
        if ($supplier->email_quote_cc) {
            $emails = array_map('trim', explode(',', $supplier->email_quote_cc));
        }
        $emails[] = $supplier->email;
        print_r($emails); die();

		/*$job_id_from = 5525;
		$job_id_to = 5548;
		for($i = $job_id_from; $i <= $job_id_to; $i++){
			$spool = new DistributePool();
        	$spool->distributeRemoval($i);
            #$spool->distributeStorage($i);
			#echo $i . '<br>';
		}*/
	}

	#To manually generate pool
	function genispoolAction($id)
	{
		$zone = ZoneLocal::findFirst($id);
        if ($zone) {
            $zone->generatePool();
        }
	}




}

