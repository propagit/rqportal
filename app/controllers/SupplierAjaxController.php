<?php

class SupplierAjaxController extends ControllerAjax
{

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

}

