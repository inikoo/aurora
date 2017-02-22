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


$date = gmdate('Y-m-d', strtotime('yesterday'));



$sql = sprintf(
    'select `Part Category Key` from `Part Category Dimension`     WHERE (`Part Category Status` in ("InUse","Discontinuing")  OR ( `Part Category Status`="NotInUse" AND DATE(`Part Category Valid To`)=%s ) ) ', $date
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $category = new Category($row['Part Category Key']);
        if ($category->id and $category->get('Category Scope') == 'Part') {

               $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];



            foreach ($timeseries_data as $time_series_data) {


                $time_series_data['Timeseries Parent']     = 'Category';
                $time_series_data['Timeseries Parent Key'] = $category->id;


                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;

                $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                $category->update_part_timeseries_record($object_timeseries, $date, $date);

            }
        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
