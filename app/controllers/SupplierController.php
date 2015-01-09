<?php

class SupplierController extends ControllerBase
{

    public function initialize()
    {
        $this->tag->setTitle('Supplier');
        parent::initialize();
        $this->view->parent = 'supplier';
    }

    public function indexAction()
    {

    }

    public function searchAction()
    {
        $this->view->suppliers = Supplier::find();
        $this->view->child = 'search';
    }

    public function viewAction($supplierId)
    {
        $this->view->supplier = Supplier::findFirst($supplierId);
        $this->view->child = 'search';
    }

    public function activateAction($supplierId)
    {
        $supplier = Supplier::findFirst($supplierId);
        $supplier->status = Supplier::ACTIVATED;
        $supplier->activation_key = md5($supplierId . '4w3s0m3');
        if ($supplier->save() == false) {

        } else {
             $this->getDI()->getMail()->send(
                array($supplier->email => $supplier->name),
                'Account Activation',
                'activation',
                array('name' => $supplier->name,
                    'activationUrl' =>
                    '/applicant/register/' . $supplier->id . '/' . $supplier->activation_key)
            );
        }
        return $this->response->redirect('supplier/search');
    }
}

