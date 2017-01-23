<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2017 at 17:15:47 GMT, Sheffield UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

class interlink {

    private $version = "Interlink express API class v1.0c";
    private $url;
    private $timeout;
    private $ch;
    private $headers;
    private $username;
    private $password;
    private $accountNo;
    private $jsonSize = 0;
    private $returnFormat = 'application/json';
    private $contentType = 'none';
    private $isPrintjob = 0;

    // Construct object
    public function __construct($url, $username, $password, $accountNo) {
        $this->url       = $url;
        $this->username  = $username;
        $this->password  = $password;
        $this->accountNo = $accountNo;
        $this->ch        = curl_init();
    }

    // Do authentication

    public function listCountry() {
        $method = "GET";
        $reqStr = "/shipping/country";
        $query  = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    // Construct headers for data transfer

    private function doQuery($method, $reqStr) {
        $this->constructHeaders();
        curl_setopt_array(
            $this->ch, array(
            CURLOPT_URL            => $this->url.$reqStr,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_USERAGENT      => $this->version,
            CURLOPT_HTTPHEADER     => $this->headers,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => (isset($this->payload)) ? $this->payload : null
        )
        );
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);



        $data     = curl_exec($this->ch);



        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        //If print job don't decode.
        if ($this->isPrintjob == 0) {
            $response = json_decode($data, true);
        } else {
            $response = $data;
        }
        if (curl_errno($this->ch)) {
            throw new Exception('Error connecting to API: '.curl_error($this->ch));
        } elseif ($httpCode === 401 || $httpCode === 403 || $httpCode === 404 || $httpCode === 500 || $httpCode === 503) {
            $this->httpError($httpCode);
        } else {
            return $response;
        }
    }

    // List shipping countries

    private function constructHeaders($headers = array()) {
        $authToken     = $this->authenticate();
        $this->headers = array(
            'Content-Type: '.$this->contentType,
            'Accept: '.$this->returnFormat,
            'GEOClient: '.$this->username.'/'.$this->accountNo,
            'GEOSession: '.$authToken,
            'Content-Length: '.$this->jsonSize
        );
    }

    // Custom Get

    private function authenticate($timeout = '5', $headers = array()) {
        $headers = array(
            'Content-Type: None',
            'Accept: application/json',
            'Authorization: Basic '.base64_encode($this->username.':'.$this->password),
            'GEOClient: '.$this->username.'/'.$this->accountNo,
            'Content-Length: 0'
        );

      //  print_r($headers);

        curl_setopt_array(
            $this->ch, array(
            CURLOPT_URL            => $this->url.'/user/?action=login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_USERAGENT      => $this->version,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => 'POST'
        )
        );
        $authPost = curl_exec($this->ch);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $data     = json_decode($authPost, true);
        if (curl_errno($this->ch)) {
            throw new Exception('Error connecting to API: '.curl_error($this->ch));
        } elseif ($httpCode === 401 || $httpCode === 403 || $httpCode === 404 || $httpCode === 500 || $httpCode === 503) {
            $this->httpError($httpCode);
        } else {
            return $data['data']['geoSession'];
        }
    }

    //Get Shipping

    public function httpError($httpCode) {
        switch ($httpCode) {
            case '401':
                throw new Exception('Username / Password incorrect');
                break;
            case '403':
                throw new Exception('Geosession header not found or invalid');
                break;
            case '404':
                throw new Exception('An attempt was made to call an API in which the URL cannot be found');
                break;
            case '500':
                throw new Exception('The ESG server had an internal error');
                break;
            case '503':
                throw new Exception('The API being called is temporary out of service');
                break;
        }
    }

    // Get country

    public function apiError($err) {
        // Probably not the ideal sollution but works and I'm not really a PHP dev. Please clean me !! :D
        if (isset($err[0])) {
            throw new Exception('API Error! Code: '.$err[0]['errorCode'].' Type: '.$err[0]['errorType'].' Message: '.$err[0]['obj'].' / '.$err[0]['errorMessage']);
        } else {
            throw new Exception('API Error! Code: '.$err['errorCode'].' Type: '.$err['errorType'].' Message: '.$err['obj'].' / '.$err['errorMessage']);
        }
    }

    //Get Network Code

    public function customGet($str) {
        $method = "GET";
        $query  = $this->doQuery($method, $str);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //Insert Shipping

    public function getShipping($data) {
        $method = "GET";
        // Needs cleaning up but regex is like Chinese to me !
        $data   = str_replace('%5D', '', str_replace('%5B', '.', http_build_query($data)));
        $reqStr = "/shipping/network/?".$data;
        $query  = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //Get Label

    public function getCountry($country) {
        $method = "GET";
        $reqStr = "/shipping/country/";
        $query  = $this->doQuery($method, $reqStr.$country);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //doQuery

    public function getNetcode($geoCode) {
        $method = "GET";
        $reqStr = "/shipping/network/".$geoCode;
        $query  = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    // Encode payload

    public function insertShipping($payload) {
        $method = "POST";
        $reqStr = "/shipping/shipment";
        $this->encodePayload($payload);
        $this->contentType = 'application/json';
        $query             = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    // Handle HTTP errors

    private function encodePayload($payload) {
        $this->payload  = json_encode($payload);
        $this->jsonSize = strlen($this->payload);
    }

    // Handle API errors

    public function getLabel($shipmentId, $dataType) {
        $method             = "GET";
        $this->returnFormat = $dataType;
        $reqStr             = "/shipping/shipment/".$shipmentId."/label/";
        $this->isPrintjob   = 1;
        $this->contentType  = 'Accept';
        $query              = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    // Destruct object

    public function __destruct() {
        curl_close($this->ch);
    }
}

?>