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
require_once 'class.Category.php';

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

$where = ' and `Part Category Key`=13227';
$where = '';

// OR ( `Part Category Status`='NotInUse' AND DATE(`Part Category Valid To`)=? ) ) ";

$sql = "SELECT `Part Category Key`,`Part Category Valid From`,`Part Category Valid To` FROM `Part Category Dimension`  where `Part Category Status`!='NotInUse'  ".$where;

$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {


    $category = new Category($row['Part Category Key']);


    if ($category->id and $category->get('Category Scope') == 'Part') {


        print $category->get('Code')."\n";

        $date1 = $row['Part Category Valid From'];
        $date2 = gmdate('Y-m-d H:i:s');

        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];


        foreach ($timeseries_data as $time_series_data) {

            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;


            $object_timeseries = new Timeseries('find', $time_series_data, 'create');


            $sql = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
            $db->prepare($sql)->execute([$object_timeseries->id]);


            $category->update_part_timeseries_record($object_timeseries, $date1, $date2);


        }
    }
}

//'NotInUse','InUse','InProcess','Discontinuing'
$sql = "SELECT `Part Category Key`,`Part Category Valid From`,`Part Category Valid To` FROM `Part Category Dimension`  where `Part Category Status` IN ('NotInUse') ".$where;

$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $category = new Category($row['Part Category Key']);
    if ($category->id and $category->get('Category Scope') == 'Part') {

        print $category->get('Code')."** \n";

        $date1 = $row['Part Category Valid From'];
        $date2 = $row['Part Category Valid To'];

        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];


        foreach ($timeseries_data as $time_series_data) {

            $time_series_data['Timeseries Parent']     = 'Category';
            $time_series_data['Timeseries Parent Key'] = $category->id;


            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;

            $object_timeseries = new Timeseries('find', $time_series_data, 'create');
            $sql               = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
            $db->prepare($sql)->execute([$object_timeseries->id]);
            $category->update_part_timeseries_record($object_timeseries, $date1, $date2);


        }
    }
}


