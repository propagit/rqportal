<?php

class SignupController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->tag->setTitle('Supplier Sign up');
        $form = new ProfileForm();

        if ($this->request->isPost())
        {
            $applicant = new Supplier();
            foreach($this->request->getPost() as $key => $value) {
                $applicant->$key = $value;
            }
            $applicant->status = Supplier::APPLIED;
            $applicant->created_on = new Phalcon\Db\RawValue('now()');

            if ($applicant->save() == false)
            {
                foreach($applicant->getMessages() as $message)
                {
                    $this->flash->error((string) $message);
                }
            }
            else
            {
                $this->flash->success('Thank you for signing up. We will contact you shortly!');
            }
        }
        $this->view->form = $form;
    }

}

