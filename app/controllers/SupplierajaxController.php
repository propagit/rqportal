<?php

class SupplierajaxController extends ControllerAjax
{

    public function getAllAction()
    {
        $request = $this->request->getJsonRawBody();
        $conditions = ""; #"status >= 0";
        $this->view->test = 'test'; return;
        if (isset($request->status))
        {
            $conditions = "status = '$request->status'";
        }

        $suppliers = Supplier::find($conditions);
        $result = array();
        foreach($suppliers as $supplier) {
            $result[] = $supplier->toArray();
        }
        $this->view->suppliers = $result;
    }

    public function searchAction($keyword)
    {
        $conditions = "user_id > 0 AND status = :status: AND
            (name LIKE :name: OR business LIKE :business: OR company LIKE :company:)";
        $parameters = array(
            'status' => Supplier::APPROVED,
            'name' => '%' . $keyword . '%',
            'business' => '%' . $keyword . '%',
            'company' => '%' . $keyword . '%'
        );
        $suppliers = Supplier::find(array(
            $conditions,
            "bind" => $parameters
        ));
        $result = array();
        foreach($suppliers as $supplier) {
            $result[] = $supplier->toArray();
        }
        $this->view->suppliers = $result;
    }

    public function rejectAction($id)
    {
        $supplier = Supplier::findFirst($id);
        $supplier->status = Supplier::REJECTED;
        $supplier->save();

        $this->mail->send(
            array($supplier->email => $supplier->name),
            'Member Application - Rejected',
            'reject',
            array('name' => $supplier->name)
        );

        $this->view->supplier = $supplier->toArray();
    }

    public function reactivateAction($id)
    {
        $supplier = Supplier::findFirst($id);
        if ($supplier->user_id)
        {
            $user = User::findFirst($supplier->user_id);
            $user->status = User::APPROVED;
            $user->save();
        }
        $supplier->status = Supplier::APPROVED;
        $supplier->save();
        $this->view->supplier = $supplier->toArray();
    }

    public function setfreeAction($id)
    {
        $supplier = Supplier::findFirst($id);
        if ($supplier->free) {
            $supplier->free = NULL;
        } else {
            $supplier->free = 1;
        }
        $supplier->save();
        $this->view->supplier = $supplier->toArray();
    }

    public function deleteAction($id)
    {
        $supplier = Supplier::findFirst($id);
        $supplier->delete();

    }

    public function deactivateAction($id)
    {
        $supplier = Supplier::findFirst($id);
        if ($supplier->user_id)
        {
            $user = User::findFirst($supplier->user_id);
            $user->status = User::INACTIVED;
            $user->save();
        }
        $supplier->status = Supplier::INACTIVED;
        $supplier->save();
        $this->view->supplier = $supplier->toArray();
    }

    public function updatenoteAction($id)
    {
        $request = $this->request->getJsonRawBody();
        $supplier = Supplier::findFirst($id);
        $supplier->note = $request->note;
        $supplier->save();
        $this->view->supplier = $supplier->toArray();
    }

    public function getnoteAction($id)
    {
        $supplier = Supplier::findFirst($id);
        $result = $supplier;
        $this->view->suppliers = $result;
    }

}

