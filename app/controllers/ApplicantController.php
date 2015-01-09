<?php

class ApplicantController extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->supplier = Supplier::findFirst("user_id = " . $auth['id']);
            $this->user = User::findFirst("id = " . $auth['id']);
        }
        $this->view->baseUrl = $this->url->get('applicant');
    }

    public function indexAction()
    {
        return $this->response->redirect('login');
    }

    public function registerAction($supplierId = null, $key = null)
    {
        $supplier = Supplier::findFirst("
            id='$supplierId'
            AND status='" . Supplier::ACTIVATED . "'
            AND key='$key'");

        if (!$supplier) {
            return $this->response->redirect('signup');
        }
        $this->tag->setTitle('Register');

        $this->view->supplier = $supplier;

        $form = new RegisterForm();
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username', 'alphanum');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }

            $user = new User();
            $user->status = User::PENDING;
            $user->username = $username;
            $user->password = md5($password);
            $user->level = User::SUPPLIER;
            if ($user->save() == false) {
                # Error
            } else {
                $supplier->key = null;
                $supplier->user_id = $user->id;
                $supplier->save();
                $this->session->set('auth', array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'status' => $user->status,
                    'level' => $user->level
                ));
                $this->response->redirect('applicant/profile');
            }

        }
        $this->view->form = $form;
    }

    public function profileAction()
    {
        $this->tag->setTitle('Profile');

        if ($this->request->isPost()) {
            # Update supplier data
            foreach($this->request->getPost() as $key => $value) {
                $this->supplier->$key = $value;
                $this->supplier->save();
                $this->response->redirect('applicant/local');
            }
        }

        $form = new ProfileForm($this->supplier, array('edit' => true));
        $this->view->form = $form;
    }

    public function localAction()
    {
        $this->tag->setTitle('Local Zones');
        $this->view->zone = 'local';
    }


    public function countryAction()
    {
        $this->tag->setTitle('Country Zones');
        $this->view->zone = 'country';
        $this->view->local_zones = ZoneLocal::find("user_id = " . $this->supplier->id);
    }

    public function interstateAction()
    {
        $this->tag->setTitle('Interstate Zones');
        $this->view->zone = 'interstate';
    }

    public function paymentAction()
    {
        $this->tag->setTitle('Payment Information');
    }

    /**
     * Ajax function
     */

    public function allLocalAction()
    {
        $this->view->disable();

        if ($this->request->isPost() == true) {
            $zones = ZoneLocal::find("user_id = " . $this->supplier->id);
            $zoneObjects = array();
            $markers = array();
            $circles = array();
            foreach($zones as $zone) {
                $zoneObjects[] = $zone->toJson();
                $circles[] = $zone->drawCircle();
                $markers[] = $zone->drawMarker();
            }
            $this->response->setContent(json_encode(array(
                'zones' => $zoneObjects,
                'circles' => $circles,
                'markers' => $markers
            )));
            return $this->response;
        }
    }

    public function addLocalAction()
    {
        $this->view->disable();

        if ($this->request->isPost() == true) {
            $payload = $this->request->getJsonRawBody();
            $zone = new ZoneLocal();
            $zone->user_id = $this->supplier->id;
            $zone->postcode = $payload->postcode;
            $zone->latitude = $payload->latitude;
            $zone->longitude = $payload->longitude;
            $zone->distance = $payload->distance;
            $zone->save();
            $this->response->setContent(json_encode(array(
                'zone' => $zone,
                'circle' => $zone->drawCircle(),
                'marker' => $zone->drawMarker()
            )));

            return $this->response;
        }
    }

    public function deleteLocalAction($id)
    {
        $this->view->disable();
        if ($this->request->isPost() == true) {
            $zone = ZoneLocal::findFirst("id = $id AND user_id = " . $this->supplier->id);
            if ($zone) {
                $zone->delete();
            }
            return $this->response;
        }
    }

    public function allCountryAction()
    {
        $this->view->disable();

        if ($this->request->isPost() == true) {
            $zones = ZoneCountry::find("user_id = " . $this->supplier->id);
            $zoneObjects = array();
            $markers = array();
            $circles = array();
            foreach($zones as $zone) {
                $zoneObjects[] = $zone->toJson();
                $circles[] = $zone->drawCircle();
                $markers[] = $zone->getLocal()->drawCircle();
            }
            $this->response->setContent(json_encode(array(
                'zones' => $zoneObjects,
                'circles' => $circles,
                'markers' => $markers
            )));
            return $this->response;
        }
    }

    public function addCountryAction()
    {
        $this->view->disable();

        if ($this->request->isPost() == true) {
            $payload = $this->request->getJsonRawBody();
            $zone_local = ZoneLocal::findFirst("id = '" . $payload->local_id . "'");
            $zone_country = new ZoneCountry();
            $zone_country->user_id = $this->supplier->id;
            $zone_country->local_id = $zone_local->id;
            $zone_country->distance = $payload->distance;
            if ($zone_country->save() == false)
            {

                $this->response->setStatusCode(400, 'Error');
            }
            else
            {
                $zone_country->postcode = $zone_local->postcode; // For display
                $this->response->setContent(json_encode(array(
                    'zone' => $zone_country,
                    'circle' => $zone_country->drawCircle(),
                    'marker' => $zone_local->drawCircle()
                )));
            }

            return $this->response;
        }
    }
    public function deleteCountryAction($id)
    {
        $this->view->disable();
        if ($this->request->isPost() == true) {
            $zone = ZoneCountry::findFirst("id = $id AND user_id = " . $this->supplier->id);
            if ($zone) {
                $zone->delete();
            }
            return $this->response;
        }
    }

    public function allInterstateAction()
    {
        $this->view->disable();

        if ($this->request->isPost() == true) {
            $zones = ZoneInterstate::find("user_id = " . $this->supplier->id);
            $zoneObjects = array();
            $circles1 = array();
            $circles2 = array();
            $paths = array();
            foreach($zones as $zone) {
                $zoneObjects[] = $zone->toJson();
                $circles1[] = $zone->drawCircle1();
                $circles2[] = $zone->drawCircle2();
                $paths[] = $zone->drawPath();
            }
            $this->response->setContent(json_encode(array(
                'zones' => $zoneObjects,
                'circles1' => $circles1,
                'circles2' => $circles2,
                'paths' => $paths
            )));
            return $this->response;
        }
    }

    public function addInterstateAction()
    {
        $this->view->disable();
        if ($this->request->isPost() == true) {
            $payload = $this->request->getJsonRawBody();
            $zone = new ZoneInterstate();
            $zone->user_id = $this->supplier->id;
            foreach($payload as $key => $value) {
                $zone->$key = $value;
            }
            if ($zone->save() == false) {
                $this->response->setStatusCode(400, 'Error');
            } else {
                $this->response->setStatusCode(200, 'OK');
                $this->response->setContent(json_encode(array(
                    'zone' => $zone,
                    'circle1' => $zone->drawCircle1(),
                    'circle2' => $zone->drawCircle2(),
                    'path' => $zone->drawPath()
                )));
            }

            return $this->response;
        }
    }
    public function deleteInterstateAction($id)
    {
        $this->view->disable();
        if ($this->request->isPost() == true) {
            $zone = ZoneInterstate::findFirst("id = $id AND user_id = " . $this->supplier->id);
            if ($zone) {
                $zone->delete();
            }
            return $this->response;
        }
    }

    public function completeAction()
    {
        $this->view->disable();
        if ($this->request->isPost() == true) {
            $url = $this->url->get('applicant/populate/' . $this->supplier->id);
            exec("curl $url > /dev/null 2>&1 &");
            $this->user->status = User::APPROVED;
            $this->user->save();
            $this->session->set('auth', array(
                'id' => $this->user->id,
                'username' => $this->user->username,
                'status' => $this->user->status,
                'level' => $this->user->level
            ));
            $this->response->setContent(json_encode(array('count' => $url)));
            return $this->response;
        }
    }

    public function populateAction($supplierId)
    {
        $this->view->disable();
        if (!$supplierId)
        {
            return false;
        }
        $zone_local = ZoneLocal::find("user_id = $supplierId");
        $count = 0;
        foreach($zone_local as $zone) {
            $count += $zone->generatePool();
        }
        $zone_country = ZoneCountry::find("user_id = $supplierId");
        foreach($zone_country as $zone) {
            $count += $zone->generatePool();
        }
        $zone_interstate = ZoneInterstate::find("user_id = $supplierId");
        foreach($zone_interstate as $zone) {
            $count += $zone->generatePool();
        }
        echo $count . ' postcodes';
    }
}

