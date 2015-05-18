<?php

class SupplierController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->setTitle('Supplier');
        $this->view->parent = 'supplier';
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
            # Add to the Queue
            $job_id = $this->queue->put(array('activation' => $supplierId));
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
	
	/*
	To manually test the distribute quote function
	function testAction()
	{
		$job_id = 118;
		$spool = new DistributePool();
        $spool->distributeRemoval($job_id);	
	}*/

}

