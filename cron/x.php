<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 12:37:44 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Timeserie.php';

require_once 'class.Supplier.php';

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
$date=gmdate('Y-m-d');

$sql = sprintf(
    'SELECT `Supplier Key`,`Supplier Code` FROM `Supplier Dimension` WHERE (`Supplier Type`!="Archived"  OR ( `Supplier Type`="Archived" AND DATE(`Supplier Valid To`)=%s ) )   AND `Supplier Code`="AWP" ', $date
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $supplier = new Supplier($row['Supplier Key']);


        $timeseries_data = $timeseries['Supplier'];

        print $row['Supplier Code']."\n";
        foreach ($timeseries_data as $time_series_data) {

  if ($time_series_data['Timeseries Frequency'] == 'Monthly') {
      $from=date('Y-m-01');

  }else{
      $from=$date;
      $to=$date;
  }



            if ($time_series_data['Timeseries Frequency'] == 'Monthly') {
                print_r($time_series_data);


                $time_series_data['Timeseries Parent']     = 'Supplier';
                $time_series_data['Timeseries Parent Key'] = $supplier->id;


                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;

                $object_timeseries = new Timeseries('find', $time_series_data, 'create');


                $supplier->update_timeseries_record($object_timeseries, $from, $to);

            }
        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
