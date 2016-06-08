<?php

class InventoryController extends \Phalcon\Mvc\Controller
{

    public function detailAction($removal_id)
    {
        $categories = RemovalInventory::findByRemovalId($removal_id);
        $data = array();
        foreach($categories as $category) {
            $c = $category->toArray();
            $c['items'] = json_decode($c['items']);
            $data[] = $c;
        }
        $this->view->categories = $data;

    }

}

