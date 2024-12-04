<?php

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';


$row = 1;
if (($handle = fopen("gpsr.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}