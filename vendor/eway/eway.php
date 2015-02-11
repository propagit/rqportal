<?php

class Eway
{
    private $endpoint = '';
    private $headers = array();
    private $ns = 'https://www.eway.com.au/gateway/managedpayment';

    public function __construct($mode)
    {
        if ($mode == 'live') {

        } else {
            $this->endpoint = 'https://www.eway.com.au/gateway/ManagedPaymentService/test/managedCreditCardPayment.asmx?WSDL';
            $this->headers = array(
                'eWAYCustomerID' => '9876543211000',
                'Username' => 'test@eway.com.au',
                'Password' => 'test123'
            );
        }
    }

    public function createCustomer($request)
    {
        try {
            $client = new SoapClient($this->endpoint, array('trace' => 1));
            $header = new SoapHeader($this->ns, 'eWAYHeader', $this->headers);
            $client->__setSoapHeaders(array($header));

            $result = $client->CreateCustomer($request);
            return $result->CreateCustomerResult;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getCustomer()
    {
        try {
            $client = new SoapClient($this->endpoint, array('trace' => 1));

            $headers = array(
                'eWAYCustomerID' => '91893390',
                'Username' => 'nam@propagate.com.au.sand',
                'Password' => 'Q1w2e3r4t5'
            );
            $header = new SoapHeader($ns, 'eWAYHeader', $headers);
            $client->__setSoapHeaders(array($header));

            $request = array(
                'managedCustomerID' => '911623975824'
            );

            $result = $client->QueryCustomer($request);
            print_r($result); die();
        } catch(Exception $e) {
            var_dump($e->getMessage()); die();
        }
    }

    public function TestCreateCustomer()
    {

        try {
            $client = new SoapClient($this->endpoint, array('trace' => 1));
            $ns = 'https://www.eway.com.au/gateway/managedpayment';
            $headers = array(
                'eWAYCustomerID' => '91893390',
                'Username' => 'nam@propagate.com.au.sand',
                'Password' => 'Q1w2e3r4t5'
            );
            $header = new SoapHeader($ns, 'eWAYHeader', $headers);
            $client->__setSoapHeaders(array($header));

            $request = array(
                'CustomerRef' => 'API',
                'Title' => 'Mr.',
                'FirstName' => 'Nam',
                'LastName' => 'Nguyen',
                'Email' => 'namnd86@gmail.com',
                'Address' => '455 Waterdale Rd',
                'Suburb' => 'Heidelberg Heights',
                'State' => 'VIC',
                'PostCode' => 3081,
                'Phone' => '0402133066',
                'Mobile' => '0402133066',
                'Fax' => '',
                'Country' => 'au',
                'Company' => 'Propagate',
                'JobDesc' => 'Leader',
                'URL' => 'http://www.propagate.com.au',
                'Comments' => '',
                'CCNameOnCard' => 'Nam Nguyen',
                'CCNumber' => '4444333322221111',
                'CCExpiryMonth' => '2',
                'CCExpiryYear' => '15'
            );
            $result = $client->CreateCustomer($request);
            print_r($result->CreateCustomerResult); die();
        } catch (Exception $e) {
            print_r($e->getMessage()); die();

        }


    }
}
