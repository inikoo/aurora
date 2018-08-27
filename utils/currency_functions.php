<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2016 at 19:05:57 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function currency_conversion($db, $currency_from, $currency_to, $update_interval = "-1 hour") {

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
                // $reload = true;

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


    $valid_currencies = array(
        'EUR',
        'AUD',
        'BGN',
        'BRL',
        'CAD',
        'CHF',
        'CNY',
        'CZK',
        'DKK',
        'GBP',
        'HKD',
        'HRK',
        'HUF',
        'IDR',
        'ILS',
        'INR',
        'JPY',
        'KRW',
        'MXN',
        'MYR',
        'NOK',
        'NZD',
        'PHP',
        'PLN',
        'RON',
        'RUB',
        'SEK',
        'SGD',
        'THB',
        'TRY',
        'USD',
        'ZAR'
    );

    if (in_array($currency_from, $valid_currencies) and in_array(
            $currency_to, $valid_currencies
        )) {

        $contents = json_decode(
            file_get_contents(
                sprintf(
                    'http://data.fixer.io/api/latest?access_key=46f6ee57415a369d42c0ea5486de8a53&base=%s&symbols=%s', $currency_from, $currency_to
                )
            ), true
        );


        if (!empty($contents['rates'][$currency_to])) {
            $exchange = floatval($contents['rates'][$currency_to]);


            $source = 'fixer.io';
            $ok     = true;
        }


    }


    if ($ok == false) {

        $api_keys = array(
            'raul@inikoo.com'      => '8158586024e345b2b798c26ee50b6987',
            'exchange1@inikoo.com' => '21467cd6ca2847cf9fdbc913e616d6e9',
            'exchange2@inikoo.com' => 'e328d66fafc94f6391d2a8e4fbab0389',
            'exchange3@inikoo.com' => '271f126537a84a3f98599e66781f8bed',
            'exchange4@inikoo.com' => '756b792276ba4c80807a85b031139d7e',
            'exchange5@inikoo.com' => '4bc72747362a496c971c528fb1b1d219',


        );
        shuffle($api_keys);
        $api_key = reset($api_keys);

        $url  = 'http://openexchangerates.org/api/latest.json?app_id='.$api_key;
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


    /*

    DOnt work for Indonesia/GBP

$exchange=get_currency($currency_from, $currency_to, 1000000)/1000000;
        if (is_numeric($exchange) and $exchange>0) {
            $exchange_rate=$exchange;




            $sql=sprintf("insert into kbase.`Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) values (%s,%f,NOW(),'Yahoo')  ON DUPLICATE KEY update `Exchange`=%f,`Currency Exchange Last Updated`=NOW(),`Currency Exchange Source`='Yahoo'",
                prepare_mysql($currency_from.$currency_to), $exchange_rate, $exchange_rate);

            $db->exec($sql);


        }

    DOnt work for Indonesia/GBP

        $url = "http://quote.yahoo.com/d/quotes.csv?s=". $currency_from . $currency_to . "=X&f=l1&e=.csv";

        $handle = fopen($url, "r");
        $contents = floatval(fread($handle, 2000));
        fclose($handle);



        if (is_numeric($contents) and $contents>0) {
            $exchange_rate=$contents;



            $sql=sprintf("insert into kbase.`Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) values (%s,%f,NOW(),'Yahoo')  ON DUPLICATE KEY update `Exchange`=%f,`Currency Exchange Last Updated`=NOW(),`Currency Exchange Source`='Yahoo'",
                prepare_mysql($currency_from.$currency_to), $exchange_rate, $exchange_rate);

            $db->exec($sql);


        }

        */


    return $exchange;
}


function get_historic_exchange($currency1, $currency2, $date) {
    //https://openexchangerates.org
    $exchange = '';

    $api_keys = array(
        'raul@inikoo.com'      => '8158586024e345b2b798c26ee50b6987',
        'exchange1@inikoo.com' => '21467cd6ca2847cf9fdbc913e616d6e9',
        'exchange2@inikoo.com' => 'e328d66fafc94f6391d2a8e4fbab0389',
        'exchange3@inikoo.com' => '271f126537a84a3f98599e66781f8bed',
        'exchange4@inikoo.com' => '756b792276ba4c80807a85b031139d7e',
        'exchange5@inikoo.com' => '4bc72747362a496c971c528fb1b1d219',


    );
    shuffle($api_keys);
    $api_key = reset($api_keys);

    $url  = 'http://openexchangerates.org/api/historical/'.$date.'.json?app_id='.$api_key;
    $data = json_decode(file_get_contents($url), true);


    if (isset($data['rates'][$currency1]) and isset($data['rates'][$currency2])) {

        $usd_cur1 = $data['rates'][$this->currency1];
        $usd_cur2 = $data['rates'][$this->currency2];
        $exchange = $usd_cur2 * (1 / $usd_cur1);


        //$source = 'openexchangerates';
    }

    return $exchange;

}


?>
