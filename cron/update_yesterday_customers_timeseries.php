<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2018 at 11:17:44 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

require_once 'class.Timeserie.php';

require_once 'class.Store.php';

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


$date = gmdate('Y-m-d', strtotime('yesterday'));


$sql = sprintf(
    'SELECT `Customer Key` FROM `Invoice Dimension` WHERE  DATE(`Invoice Date`)=%s  ', $date
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $customer = get_object('Customer', $row['Customer Key']);
        $customer->update_invoices();

        $timeseries_data = $timeseries['Customer'];


        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Customer';
            $time_series_data['Timeseries Parent Key'] = $customer->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;

            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $customer->update_timeseries_record($object_timeseries, $date, $date);


        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
