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
        $supplier->key = md5($supplierId . '4w3s0m3');
        $supplier->save();
        return $this->response->redirect('supplier/search');
    }
}

