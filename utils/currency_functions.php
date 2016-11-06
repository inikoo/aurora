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

    $reload        = false;
    $in_db         = false;
    $exchange_rate = 1;


    //get info from database;
    $sql = sprintf(
        "SELECT * FROM kbase.`Currency Exchange Dimension` WHERE `Currency Pair`=%s", prepare_mysql($currency_from.$currency_to)
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $date1 = $row['Currency Exchange Last Updated'];
            $date2 = gmdate("Y-m-d H:i:s", strtotime('now '.$update_interval));


            if (strtotime($date1) < strtotime($date2)) {

                $reload = true;


            }
            $exchange_rate = $row['Exchange'];

        } else {
            $reload = true;
            $in_db  = false;
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
        )
    ) {

        $contents = json_decode(
            file_get_contents(
                sprintf(
                    'http://api.fixer.io/latest?base=%s&symbols=%s', $currency_from, $currency_to
                )
            ), true
        );

        $exchange = floatval($contents['rates'][$currency_to]);


        $source = 'fixer.io';


    } else {
        $url = "http://quote.yahoo.com/d/quotes.csv?s=".$currency_from.$currency_to."=X&f=l1&e=.csv";

        $handle   = fopen($url, "r");
        $exchange = floatval(fread($handle, 2000));
        fclose($handle);


        $source = 'YAHOO';

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


function get_currency($from_Currency, $to_Currency, $amount) {
    $amount        = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency   = urlencode($to_Currency);

    $url
        = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
    print $url;

    $ch      = curl_init();
    $timeout = 0;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt(
        $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"
    );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('bld>', $rawdata);
    $data = explode($to_Currency, $data[1]);


    return $data[0];
}


?>
