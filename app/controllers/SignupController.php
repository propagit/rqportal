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

            $applicant->name = $this->request->getPost('name');
            $applicant->business = $this->request->getPost('business');
            $applicant->address = $this->request->getPost('address');
            $applicant->suburb = $this->request->getPost('suburb');
            $state_id = $this->request->getPost('state');
            if ($state_id)
            {
                $applicant->state = State::findFirst("id = $state_id")->name;
            }

            $applicant->postcode = $this->request->getPost('postcode');
            $applicant->phone = $this->request->getPost('phone');
            $applicant->email = $this->request->getPost('email');
            $applicant->website = $this->request->getPost('website');
            $applicant->about = $this->request->getPost('about');
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
                $this->flash->success('Thank you for signing up. We will contact you shortly!')
            }
        }
        $this->view->form = $form;
    }

}

