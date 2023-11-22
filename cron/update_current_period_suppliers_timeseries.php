<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 12:37:44 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

require_once 'class.Timeserie.php';

require_once 'class.Supplier.php';
require_once 'class.Agent.php';

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

$timeseries = get_time_series_config();



$sql = sprintf(
    'SELECT `Agent Key`,`Agent Code` FROM `Agent Dimension`  ',
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $agent = new Agent($row['Agent Key']);


        $timeseries_data = $timeseries['Agent'];


        foreach ($timeseries_data as $time_series_data) {



            $to   = gmdate('Y-m-d');

            if ($time_series_data['Timeseries Frequency'] == 'Daily') {
                $from = gmdate('Y-m-d', strtotime('yesterday'));

            }elseif ($time_series_data['Timeseries Frequency'] == 'Monthly') {
                $from = gmdate('Y-m-01');


            }elseif ($time_series_data['Timeseries Frequency'] == 'Yearly') {
                $from = gmdate('Y-01-01');

            }elseif ($time_series_data['Timeseries Frequency'] == 'Quarterly') {



                $sql = sprintf(
                    "SELECT  MAKEDATE(YEAR(`Date`), 1) + INTERVAL QUARTER(`Date`) QUARTER - INTERVAL 1 QUARTER  AS start FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s)  GROUP BY Yearweek(`Date`,5) ",
                    prepare_mysql(gmdate('Y-m-d')), prepare_mysql(gmdate('Y-m-d'))
                );

                if ($result2=$db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $from=$row2['start'];
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }elseif ($time_series_data['Timeseries Frequency'] == 'Weekly') {
                $sql = sprintf(
                    "SELECT  DATE_ADD(`Date`, INTERVAL(-WEEKDAY(`Date`)) DAY) AS  start FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s)  GROUP BY Yearweek(`Date`,5) ",
                    prepare_mysql(gmdate('Y-m-d')), prepare_mysql(gmdate('Y-m-d'))
                );

                if ($result2=$db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $from=$row2['start'];
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }



            $time_series_data['Timeseries Parent']     = 'Agent';
            $time_series_data['Timeseries Parent Key'] = $agent->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;

            $object_timeseries = new Timeseries('find', $time_series_data, 'create');


            $agent->update_timeseries_record($object_timeseries, $from, $to);


        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}





$date = gmdate('Y-m-d', strtotime('yesterday'));
$date = gmdate('Y-m-d', strtotime('today -2 months'));


$sql = sprintf(
    'SELECT `Supplier Key`,`Supplier Code` FROM `Supplier Dimension` WHERE (`Supplier Type`!="Archived"  OR ( `Supplier Type`="Archived" AND DATE(`Supplier Valid To`)=%s ) ) ', $date
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $supplier = new Supplier($row['Supplier Key']);


        $timeseries_data = $timeseries['Supplier'];


        foreach ($timeseries_data as $time_series_data) {



            $to   = gmdate('Y-m-d');

            if ($time_series_data['Timeseries Frequency'] == 'Daily') {
                $from = gmdate('Y-m-d', strtotime('yesterday'));

            }elseif ($time_series_data['Timeseries Frequency'] == 'Monthly') {
                $from = gmdate('Y-m-01');


            }elseif ($time_series_data['Timeseries Frequency'] == 'Yearly') {
                $from = gmdate('Y-01-01');

            }elseif ($time_series_data['Timeseries Frequency'] == 'Quarterly') {



                 $sql = sprintf(
                     "SELECT  MAKEDATE(YEAR(`Date`), 1) + INTERVAL QUARTER(`Date`) QUARTER - INTERVAL 1 QUARTER  AS start FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s)  GROUP BY Yearweek(`Date`,5) ",
                     prepare_mysql(gmdate('Y-m-d')), prepare_mysql(gmdate('Y-m-d'))
                 );

                if ($result2=$db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $from=$row2['start'];
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }elseif ($time_series_data['Timeseries Frequency'] == 'Weekly') {
                $sql = sprintf(
                    "SELECT  DATE_ADD(`Date`, INTERVAL(-WEEKDAY(`Date`)) DAY) AS  start FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s)  GROUP BY Yearweek(`Date`,5) ",
                    prepare_mysql(gmdate('Y-m-d')), prepare_mysql(gmdate('Y-m-d'))
                );

                if ($result2=$db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $from=$row2['start'];
                	}
                }else {
                	print_r($error_info=$db->errorInfo());
                	print "$sql\n";
                	exit;
                }


            }

         

            $time_series_data['Timeseries Parent']     = 'Supplier';
            $time_series_data['Timeseries Parent Key'] = $supplier->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;

            $object_timeseries = new Timeseries('find', $time_series_data, 'create');


            $supplier->update_timeseries_record($object_timeseries, $from, $to);


        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


