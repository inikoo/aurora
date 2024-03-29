<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28-06-2019 17:36:19 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$XMLContent = file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");


$date = gmdate('Y-m-d');

foreach ($XMLContent as $line) {

    if (preg_match("/time='(\d{4}-\d{2}-\d{2})'/", $line, $_date)) {
        $date = $_date[1];

    }


    if (preg_match("/currency='([[:alpha:]]+)'/", $line, $currencyCode)) {
        if (preg_match("/rate='([[:graph:]]+)'/", $line, $rate)) {


            $_value        = $rate[1];
            $currency_code = $currencyCode[1];

            if (is_numeric($_value)) {


                $sql = 'insert into kbase.`ECB Currency Exchange Dimension` (`ECB Currency Exchange Date`,`ECB Currency Exchange Currency Pair`,`ECB Currency Exchange Rate`) values (?,?,?) ON DUPLICATE KEY UPDATE `ECB Currency Exchange Rate`=?';
                $db->prepare($sql)->execute(
                    array(
                        $date,
                        'EUR'.$currency_code,
                        $_value,
                        $_value
                    )
                );


                if ($_value != 0) {
                    $sql = 'insert into kbase.`ECB Currency Exchange Dimension` (`ECB Currency Exchange Date`,`ECB Currency Exchange Currency Pair`,`ECB Currency Exchange Rate`) values (?,?,?) ON DUPLICATE KEY UPDATE `ECB Currency Exchange Rate`=? ';
                    $rate= 1 / $_value;
                    $db->prepare($sql)->execute(
                        array(
                            $date,
                            $currency_code.'EUR',
                            $rate,
                            $rate
                        )
                    );




                }


            }

        }
    }
}
