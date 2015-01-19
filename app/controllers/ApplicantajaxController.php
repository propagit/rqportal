<?php

class ApplicantajaxController extends ControllerAjax
{

    public function indexAction()
    {
        $this->view->test = array(1 => 2);
    }

}

