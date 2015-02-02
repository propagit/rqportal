<?php

class SupplierajaxController extends ControllerAjax
{

    public function getAllAction()
    {
        $suppliers = Supplier::find("status >= 0");
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
        $job_id = $this->queue->put(array('reject' => $id));
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
    }

}

