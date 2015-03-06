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
        'admin' => array(
            'icon' => 'fa-users',
            'label' => 'Back to Admin',
            'action' => 'login/admin'
        ),
        'dashboard' => array(
            'icon' => 'fa-dashboard',
            'label' => 'Dashboard',
            'action' => 'dashboard'
        ),
        'supplier' => array(
            'icon' => 'fa-user',
            'label' => 'Manage Supplier',
            'action' => 'supplier'
        ),
        'profile' => array(
            'icon' => 'fa-user',
            'label' => 'Update Profile',
            'action' => '#',
            'children' => array(
                'company' => 'Company Profile',
                'password' => 'Password',
                'location' => 'Work Locations',
                'filter' => 'Quote Filter',
                'payment' => 'Payment Info'
            )
        ),
        'quote' => array(
            'icon' => 'fa-comment-o',
            'label' => 'Manage Quotes',
            'action' => 'quote'
        ),
        'billing' => array(
            'icon' => 'fa-file-text-o',
            'label' => 'Manage Billing',
            'action' => '#',
            'children' => array(
                'invoice' => 'Search Invoices',
                'quote' => 'Outstanding Quotes',
                'create' => 'Create Invoice'
            )
        ),
        'setting' => array(
            'icon' => 'fa-cog',
            'label' => 'Settings',
            'action' => 'setting'
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
            unset($this->_menu['dashboard']);
            unset($this->_menu['supplier']);
            unset($this->_menu['setting']);
            if (!isset($auth['is_admin'])) {
                unset($this->_menu['admin']);
            }
            $this->_menu['billing'] = array(
                'icon' => 'fa-file-text-o',
                'label' => 'Manage Invoices',
                'action' => 'billing/invoice'
            );
        } else {
            unset($this->_menu['profile']);
            unset($this->_menu['admin']);
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
            $url = ($option['action'] == '#') ? '#' : $baseUrl . $option['action'];
            echo '<a href="' . $url . '"><i class="fa fa-2x fa-fw ' . $option['icon'] . '"></i> <span class="menu-item-parent">' . $option['label'] . '</span></a>';
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

    public function getBreadcrumb()
    {
        $controller = $this->view->getControllerName();
        $action = $this->view->getActionName();
        echo '<ol class="breadcrumb">';
        echo '<li><a href="' . $this->url->get('') . '"><i class="fa fa-home"></i></a></li>';
        echo '<li><a href="' . $this->url->get($controller) . '">' . ucwords($controller) . '</a></li>';
        if ($action && $action != 'index') {
            echo '<li>' . ucwords($action) . '</li>';
        }
        echo '</ol>';
    }

    public function getUsername()
    {
        $auth = $this->session->get('auth');
        echo $auth['username'];
    }

    public function getAccessLevel()
    {
        $auth = $this->session->get('auth');
        echo $auth['level'];
    }

    public function isAdmin()
    {
        $auth = $this->session->get('auth');
        if ($auth['level'] == User::ADMIN) {
            return true;
        }
        return false;
    }

    public function countNewQuote()
    {
        $auth = $this->session->get('auth');

        $conditions = "created_on LIKE :today:";
        $parameters = array(
            'today' => date('Y-m-d') . '%'
        );

        if ($auth['level'] == User::SUPPLIER) {
            $conditions .= "AND user_id = :user_id:";
            $parameters['user_id'] = $auth['id'];
        }

        $quotes = Quote::find(array(
            $conditions,
            "bind" => $parameters,
            "group" => array('job_id', 'job_type')
        ));
        return count($quotes);
    }

    public function ewayTitle($field_name='title', $value = '')
    {
        echo '<select ng-model="form.title" name="' . $field_name . '" class="form-control" id="' . $field_name . '">';
        echo '  <option value="">Select</option>';
        foreach(array('Mr.', 'Ms.', 'Mrs.', 'Miss', 'Dr.', 'Sir.', 'Prof.') as $title) {
            echo '<option value="' . $title . '"' . ($value == $title ? ' selected="selected"' : '') . '>' . $title . '</option>';
        }
        echo '</select>';
    }

    public function cardMonth($field_name='ccexpmonth', $value='')
    {
        echo '<select ng-model="form.ccexpmonth" name="' . $field_name . '" class="form-control" id="' . $field_name . '">';
        echo '  <option value="">Month</option>';
        for($month=1; $month <= 12; $month++) {
            echo '<option value="' . $month . '"' . ($value == $month ? ' selected="selected"' : '') . '>' . date("m", mktime(0, 0, 0, $month, 10)) . '</option>';
        }
        echo '</select>';
    }

    public function cardYear($field_name='ccexpyear', $value='')
    {
        echo '<select ng-model="form.ccexpyear" name="' . $field_name . '" class="form-control" id="' . $field_name . '">';
        echo '  <option value="">Year</option>';
        for($i=0; $i <= 10; $i++) {
            echo '<option value="' . (date('y') + $i) . '"' . ($value == (date('y') + $i) ? ' selected="selected"' : '') . '>' . (date('Y') + $i) . '</option>';
        }
        echo '</select>';
    }
}
