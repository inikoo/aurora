<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 December 2017 at 21:55:29 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';

// download this file from  https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html
$filename = 'cron/eurofxref-hist.csv';


$data = array_map('str_getcsv', file($filename));

$index = 0;

$currency_codes = array();

foreach ($data as $key => $row) {


    if ($index == 0) {
        foreach ($row as $_key => $_value) {
            $currency_codes[$_key] = $_value;
        }

    } else {


        foreach ($row as $_key => $_value) {
            // print "$_key $_value\n";


            if ($_key == 0) {
                $date = $_value;
             //   print "$date\n";

            } else {
                if (is_numeric($_value)) {

                    if (isset($currency_codes[$_key])) {
                        $currency_code = $currency_codes[$_key];
                        $sql           = sprintf('insert into kbase.`ECB Currency Exchange Dimension` (`ECB Currency Exchange Date`,`ECB Currency Exchange Currency Pair`,`ECB Currency Exchange Exchange Rate`) values (%s,%s,%s)', prepare_mysql($date), prepare_mysql('EUR'.$currency_code), $_value);
                        $db->exec($sql);
                        //print $sql."\n";


                        if ($_value != 0) {
                            $sql = sprintf('insert into kbase.`ECB Currency Exchange Dimension` (`ECB Currency Exchange Date`,`ECB Currency Exchange Currency Pair`,`ECB Currency Exchange Exchange Rate`) values (%s,%s,%s)', prepare_mysql($date), prepare_mysql($currency_code.'EUR'), 1 / $_value);
                            $db->exec($sql);
                            //print $sql."\n";
                        }
                    }


                }


            }


        }

    }
    $index++;

}