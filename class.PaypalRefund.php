<?php

/**
 *
 * @author     Mubashir Ali
 * saad_ali6@yahoo.com
 * @copyright  GNU
 * @example    example.php
 * @filesource class.refund.php
 * @version    1.0
 *
 * This PayPal API provides the functionality of Refunding Amount.
 * Credentials are omitted from here for privacy purpose. To use it credentials are compulsory to provide.
 */
class PayPalRefund {
    private $API_Username, $API_Password, $Signature, $API_Endpoint, $version;

    function __construct($_API_Username, $_API_Password, $_Signature, $_API_Endpoint) {
        $mode = "live";

        if ($mode == "live")       // you might want to keep the below 4 lines in the DB ************
        {


            $this->API_Username = $_API_Username;
            $this->API_Password = $_API_Password;
            $this->Signature    = $_Signature;
            $this->API_Endpoint = $_API_Endpoint;

        } else {       //not used when in live
            $this->API_Username = "xxxxx_xxxxxxxxxx_biz_api1.xxx.xxx.xx";
            $this->API_Password = "xxxxxxxxxx";
            $this->Signature
                                = "xxxxxxxxxxxxxxxxx.xxxxxxxxxxxxx-xxxxxxxxxxxxx-xxxxxxxxxx";
            $this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
        }
        $this->version = "51.0";
    }

    /**
     * @param array $aryData
     *
     * @return array
     */
    function refundAmount($aryData) {
        if (trim(@$aryData['currencyCode']) == "") {
            return array("ERROR_MESSAGE" => "Currency Code is Missing");
        }
        if (trim(@$aryData['refundType']) == "") {
            return array("ERROR_MESSAGE" => "Refund Type is Missing");
        }
        if (trim(@$aryData['transactionID']) == "") {
            return array("ERROR_MESSAGE" => "Transaction ID is Missing");
        }

        $requestString
            = "&TRANSACTIONID={$aryData['transactionID']}&REFUNDTYPE={$aryData['refundType']}&CURRENCYCODE={$aryData['currencyCode']}";

        if (trim(@$aryData['invoiceID']) != "") {
            $requestString .= "&INVOICEID={$aryData['invoiceID']}";
        }

        if (isset($aryData['memo'])) {
            $requestString .= "&NOTE={$aryData['memo']}";
        }

        if (strcasecmp($aryData['refundType'], 'Partial') == 0) {
            if (!isset($aryData['amount'])) {
                return array("ERROR_MESSAGE" => "For Partial Refund - It is essential to mention Amount");
            } else {
                $requestString = $requestString."&AMT={$aryData['amount']}";
            }

            if (!isset($aryData['memo'])) {
                return array("ERROR_MESSAGE" => "For Partial Refund - It is essential to enter text for Memo");
            }
        }

        $resCurl = $this->sendRefundRequest($requestString);

        return $resCurl;
    }

    /**
     * This function actually Sends the CURL Request for Refund
     *
     * @param string - $requestString
     *
     * @return array - returns the response
     */
    function sendRefundRequest($requestString) {
        $this->API_UserName  = urlencode($this->API_Username);
        $this->API_Password  = urlencode($this->API_Password);
        $this->API_Signature = urlencode($this->Signature);

        $this->version = urlencode($this->version);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $reqStr
            = "METHOD=RefundTransaction&VERSION={$this->version}&PWD={$this->API_Password}&USER={$this->API_UserName}&SIGNATURE={$this->API_Signature}$requestString";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $reqStr);

        // Get response from the server.
        $curlResponse = curl_exec($ch);

        if (!$curlResponse) {
            return array(
                "ERROR_MESSAGE" => "RefundTransaction failed".curl_error($ch)."(".curl_errno($ch).")"
            );
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $curlResponse);

        $aryResponse = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $aryResponse[$tmpAr[0]] = urldecode($tmpAr[1]);
            }
        }

        if ((0 == sizeof($aryResponse))
            || !array_key_exists(
                'ACK', $aryResponse
            )
        ) {
            return array("ERROR_MESSAGE" => "Invalid HTTP Response for POST request ($reqStr) to {$this->API_Endpoint}");
        }

        return $aryResponse;
    }
}

?>