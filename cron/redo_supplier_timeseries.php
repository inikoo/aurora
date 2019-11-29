<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 13:31:36 GMT+8, Cyberjaya, Malaysia
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

//$where = ' and `Supplier Key`=6472';
$where = '';


$sql = "SELECT `Supplier Key`,`Supplier Valid From`,`Supplier Valid To` FROM `Supplier Dimension`  where `Supplier Type`!='Archived'  ".$where;

$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {


    $supplier = new Supplier($row['Supplier Key']);


    print $supplier->get('Code')."\n";

    $date1 = $row['Supplier Valid From'];
    $date2 = gmdate('Y-m-d H:i:s');

    $timeseries_data = $timeseries['Supplier'];


    foreach ($timeseries_data as $time_series_data) {

        $time_series_data['Timeseries Parent']     = 'Supplier';
        $time_series_data['Timeseries Parent Key'] = $supplier->id;


        $editor['Date']             = gmdate('Y-m-d H:i:s');
        $time_series_data['editor'] = $editor;


        $object_timeseries = new Timeseries('find', $time_series_data, 'create');


        $sql = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
        $db->prepare($sql)->execute([$object_timeseries->id]);


        $supplier->update_timeseries_record($object_timeseries, $date1, $date2);


    }

}

$sql = "SELECT `Supplier Key`,`Supplier Valid From`,`Supplier Valid To` FROM `Supplier Dimension`  where `Supplier Type`='Archived' ".$where;

$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $supplier = new Supplier($row['Supplier Key']);

    print $supplier->get('Code')."** \n";

    $date1 = $row['Supplier Valid From'];
    $date2 = $row['Supplier Valid To'];

    $timeseries_data = $timeseries['Supplier'];


    foreach ($timeseries_data as $time_series_data) {

        $time_series_data['Timeseries Parent']     = 'Supplier';
        $time_series_data['Timeseries Parent Key'] = $supplier->id;


        $editor['Date']             = gmdate('Y-m-d H:i:s');
        $time_series_data['editor'] = $editor;

        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
        $sql               = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
        $db->prepare($sql)->execute([$object_timeseries->id]);
        $supplier->update_timeseries_record($object_timeseries, $date1, $date2);


    }
}


