<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{

    private $_menu = array(
        'dashboard' => array(
            'icon' => 'fa-dashboard',
            'label' => 'Dashboard',
            'action' => 'dashboard'
        ),
        'supplier' => array(
            'icon' => 'fa-user',
            'label' => 'Manage Supplier',
            'action' => '#',
            'children' => array(
                'search' => 'Search Supplier',
                'add' => 'Add Supplier'
            )
        ),
        'profile' => array(
            'icon' => 'fa-user',
            'label' => 'Update Profile',
            'action' => ''
        ),
        'quote' => array(
            'icon' => 'fa-file-text-o',
            'label' => 'Manage Quotes',
            'action' => '#',
            'children' => array(
                'search' => 'Search Quotes'
            )
        ),
        'billing' => array(
            'icon' => 'fa-bank',
            'label' => 'Manage Billing',
            'action' => '#'
        ),
        'setting' => array(
            'icon' => 'fa-cog',
            'label' => 'Settings',
            'action' => '#'
        )
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {

        $auth = $this->session->get('auth');

        if ($auth['level'] == User::SUPPLIER) {
            unset($this->_menu['supplier']);
        } else {
            unset($this->_menu['profile']);
        }
        $baseUrl = $this->url->get('');
        $currentController = $this->view->getControllerName();
        $currentAction = $this->view->getActionName();
        echo '<ul>';
        foreach ($this->_menu as $controller => $option) {
            if ($currentController == $controller) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            $url = ($option['action'] == '#') ? '#' : $baseUrl . $controller;
            echo '<a href="' . $url . '"><i class="fa fa-2x fa-fw ' . $option['icon'] . '"></i> ' . $option['label'] . '</a>';
            if (isset($option['children'])) {
                echo '<ul>';
                foreach($option['children'] as $action => $label) {
                    if ($currentAction == $action && $currentController == $controller) {
                        echo '<li class="active">';
                    } else {
                        echo '<li>';
                    }
                    echo '<a href="' . $baseUrl . $controller . '/' . $action . '">' . $label . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
        echo '</ul>';

    }

    public function getUsername()
    {
        $auth = $this->session->get('auth');
        echo $auth['username'];
    }
}
