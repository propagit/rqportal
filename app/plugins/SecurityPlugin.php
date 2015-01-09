<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * Security Plugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */

class SecurityPlugin extends Plugin
{

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        // throw new \Exception("something");
        if (!isset($this->persistent->acl))
        {
            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            # Register roles
            $roles = array(
                'guests' => new Role('Guest'),
                'applicant' => new Role('Applicant'),
                'user' => new Role('User'),
                'admin' => new Role('Admin')
            );
            foreach($roles as $role) {
                $acl->addRole($role);
            }

            # Admin area resources
            $adminResources = array(
                'dashboard' => array('index'),
                'setting' => array('index'),
                'user' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete')
            );
            foreach($adminResources as $resource => $actions) {
                $acl->addResource(new Resource($resouce), $actions);
            }

            # User area resources
            $userResources = array(
                'invoice' => array('index')
            );
            foreach($userResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            # Applicant area resources
            $applicantResources = array(
                'applicant' => array('index', 'profile', 'location', 'payment')
            );
            foreach($applicantResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            # Public area resources
            $publicResources = array(
                'signup' => array('index'),
                'login' => array('index')
            );
            foreach($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            # Grant access to public areas to all roles
            // foreach ($roles as $role) {
            //     foreach ($publicResources as $resource => $actions) {
            //         foreach ($actions as $action){
            //             $acl->allow($role->getName(), $resource, $action);
            //         }
            //     }
            // }

            # Grant access to applicant area to role Applicant
            foreach($applicantResources as $resource => $action) {
                foreach($actions as $action) {
                    $acl->allow('Applicant', $resource, $action);
                }
            }

            # Grant access to user area to role User
            foreach($userResources as $resource => $action) {
                foreach($actions as $action) {
                    $acl->allow('User', $resource, $action);
                }
            }

            # Grant access to admin area to role Admin
            foreach($adminResources as $resource => $action) {
                foreach($actions as $action) {
                    $acl->allow('Admin', $resource, $action);
                }
            }

            # The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }

    /**
     * This action is executed before excute any action in the application
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {

        $level = $this->session->get('level');
        if ($level == 9) {
            $role = 'Admin';
        } else if ($level == 3) {
            $role = 'User';
        } else if ($level == 1) {
            $role = 'Applicant';
        } else {
            $role = 'Guest';
        }
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();
return xdebug_print_function_stack("stop here!");
        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $dispatcher->forward(array(
                'controller' => 'login',
                'action'     => 'index'
            ));
            return false;
        }
    }
}
