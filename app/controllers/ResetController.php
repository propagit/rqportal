<?php

class ResetController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->tag->setTitle('Reset Your Password');

        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');

            $user = User::findFirst(array(
                "username = :username: AND status = :status: AND level = :level:",
                'bind' => array(
                    'username' => $username,
                    'status' => User::APPROVED,
                    'level' => User::SUPPLIER
                )
            ));
            if (!$user) {
                $this->flash->error('Username not found');
            } else {
                $user->reset_key = md5(Helper::random_string());
                if ($user->save()) {
                    # Add to the Queue
                    $job_id = $this->queue->put(array('reset_instruction' => $user->id));
                } else {
                    $this->flash->error('There was system error');
                }
            }
        }
    }

    public function confirmAction($user_id, $reset_key)
    {
        if (!$user_id || !$reset_key) {
            $this->response->redirect('');
        }
        $user = User::findFirst(array(
            "id = :id: AND reset_key = :reset_key:",
            'bind' => array('id' => $user_id, 'reset_key' => $reset_key)
        ));
        if (!$user) {
            $this->response->redirect('');
        }

        $this->session->set('auth', array(
            'id' => $user->id,
            'username' => $user->username,
            'status' => $user->status,
            'level' => $user->level
        ));
        # We are sure this is for the supplier only
        $this->response->redirect('profile/password');
    }

}

