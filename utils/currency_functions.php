<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2016 at 19:05:57 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function currency_conversion($db, $currency_from, $currency_to, $update_interval = "-1 hour") {



    require 'keyring/currency_exchange_api_keys.php';

    $currency_from = strtoupper($currency_from);
    $currency_to   = strtoupper($currency_to);

    if ($currency_from == $currency_to) {
        return 1;
    }

    $ok       = false;
    $exchange = '1';// <-- recipe for disaster is just a fail over if is not internet during development
    $source   = 'FailOver';




    $sql = sprintf(
        "SELECT * FROM kbase.`Currency Exchange Dimension` WHERE `Currency Pair`=%s", prepare_mysql($currency_from.$currency_to)
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $date1 = $row['Currency Exchange Last Updated'];
            $date2 = gmdate("Y-m-d H:i:s", strtotime('now '.$update_interval));

            if (strtotime($date1) > strtotime($date2)) {
                           return $row['Exchange'];
            }
        } else {
            //$reload = true;
            //$in_db  = false;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $api_keys = $currency_exhange_api_keys['apilayer'];
    shuffle($api_keys);
    $api_key = reset($api_keys);



    set_error_handler(
        function ($severity, $message, $file, $line) {
            throw new ErrorException($message, $severity, $severity, $file, $line);
        }
    );

    try {

        $exchage_data=file_get_contents(sprintf('http://www.apilayer.net/api/live?access_key=%s&format=1&currencies=%s,%s', $api_key, $currency_from, $currency_to));
        $contents = json_decode($exchage_data, true);

    }
    catch (Exception $e) {
        $contents=array();
    }




    if (!empty($contents['quotes']['USD'.$currency_from]) and !empty($contents['quotes']['USD'.$currency_to])) {
        $usd_cur1 = $contents['quotes']['USD'.$currency_from];
        $usd_cur2 = $contents['quotes']['USD'.$currency_to];
        $exchange = $usd_cur2 * (1 / $usd_cur1);
        $source = 'apilayer';
        $ok     = true;
    }

    if ($ok == false) {
        $api_keys = $currency_exhange_api_keys['copenexchange'];
        shuffle($api_keys);
        $api_key = reset($api_keys);
        $url = 'http://openexchangerates.org/api/latest.json?app_id='.$api_key;
        $data = json_decode(file_get_contents($url), true);
        if (isset($data['rates'][$currency_from]) and isset($data['rates'][$currency_to])) {

            $usd_cur1 = $data['rates'][$currency_from];
            $usd_cur2 = $data['rates'][$currency_to];
            $exchange = $usd_cur2 * (1 / $usd_cur1);

            $source = 'openexchangerates';
        }


    }


    if (is_numeric($exchange) and $exchange > 0) {

        $sql = sprintf(
            "INSERT INTO kbase.`Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) VALUES (%s,%f,NOW(),%s)  ON DUPLICATE KEY UPDATE `Exchange`=%f,`Currency Exchange Last Updated`=NOW(),`Currency Exchange Source`=%s",
            prepare_mysql($currency_from.$currency_to), $exchange, prepare_mysql($source), $exchange, prepare_mysql($source)
        );


        $db->exec($sql);


    }





    return $exchange;
}

function get_historic_exchange($currency1, $currency2, $date) {

    require 'keyring/currency_exchange_api_keys.php';


    //https://openexchangerates.org
    $exchange = '';



    $api_keys = $currency_exhange_api_keys['copenexchange'];
    shuffle($api_keys);
    $api_key = reset($api_keys);


    $url  = 'http://openexchangerates.org/api/historical/'.$date.'.json?app_id='.$api_key;
    $data = json_decode(file_get_contents($url), true);


    if (isset($data['rates'][$currency1]) and isset($data['rates'][$currency2])) {

        $usd_cur1 = $data['rates'][$currency1];
        $usd_cur2 = $data['rates'][$currency2];
        $exchange = $usd_cur2 * (1 / $usd_cur1);


        //$source = 'openexchangerates';
    }

    return $exchange;

}



