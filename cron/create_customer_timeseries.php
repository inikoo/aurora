<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 11:58:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


require_once 'utils/date_functions.php';
require_once 'conf/timeseries.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$where = '';

$sql = sprintf("select count(*) as num from `Customer Dimension` $where");
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;


$sql = sprintf('SELECT `Timeseries Key` FROM `Timeseries Dimension`  where `Timeseries Type`="CustomerSales"');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf(
            'delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=%d ', $row['Timeseries Key']
        );
        $db->exec($sql);
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
$sql = sprintf('delete FROM `Timeseries Dimension`  where `Timeseries Type`="CustomerSales"');
$db->exec($sql);


$timeseries = get_time_series_config();


$sql = sprintf('SELECT `Customer Key` FROM `Customer Dimension`   order by `Customer Key` desc ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $customer = get_object('Customer', $row['Customer Key']);

        $customer->update_invoices();


        $timeseries_data = $timeseries['Customer'];

        foreach ($timeseries_data as $time_series_data) {


                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;

              //  print_r($time_series_data);

                $customer->create_timeseries($time_series_data);




        }


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
