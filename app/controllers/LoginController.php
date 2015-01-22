<?php

class LoginController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->_dispatcherUser();

        $this->tag->setTitle('Login');
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = User::findFirst(array(
                "username = :username: AND password = :password:",
                'bind' => array('username' => $username, 'password' => md5($password))
            ));
            // var_dump($user);
            if ($user != false) {
                $this->_registerSession($user);
                $this->_dispatcherUser();
            }
            $this->flash->error('Wrong username/password');
            #$this->session->set('level', 9);
            #return $this->response->redirect('dashboard');
        }
    }

    public function adminAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['is_admin']))
        {
            $this->session->set('auth', $auth['admin_auth']);
            return $this->response->redirect('supplier');
        }
        $this->_dispatcherUser();
    }

    private function _registerSession(User $user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'username' => $user->username,
            'status' => $user->status,
            'level' => $user->level
        ));
    }

    private function _dispatcherUser()
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            return false;
        }
        if ($auth['status'] == User::APPROVED) {
            return $this->response->redirect('dashboard');
        } else if ($auth['level'] == User::SUPPLIER) {
            return $this->response->redirect('applicant/profile');
        }
    }

}

