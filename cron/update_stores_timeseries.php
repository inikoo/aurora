<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 14:11:28 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

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
    'SELECT `Store Key` FROM `Store Dimension` WHERE (`Store State`="Normal"  OR ( `Store State`="Closed" AND DATE(`Store Valid To`)=%s ) ) ', $date
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $store = new Store($row['Store Key']);


        $timeseries_data = $timeseries['Store'];


        foreach ($timeseries_data as $time_series_data) {


            $time_series_data['Timeseries Parent']     = 'Store';
            $time_series_data['Timeseries Parent Key'] = $store->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;

            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $store->update_timeseries_record($object_timeseries, $date, $date);


        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
