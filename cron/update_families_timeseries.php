<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 13:53:51 GMT+8, Cyberjaya, Malaysia
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
////'In Process','Active','Suspended','Discontinued'

$sql = sprintf(
    'select `Product Category Key` from `Product Category Dimension`     WHERE (`Product Category Status` in ("Active","Discontinuing","Suspended","In Process")  OR ( `Product Category Status`="Discontinued" AND DATE(`Product Category Valid To`)=%s ) ) ', $date
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $category = new Category($row['Product Category Key']);
        if ($category->id and $category->get('Category Scope') == 'Product') {

               $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];



            foreach ($timeseries_data as $time_series_data) {


                $time_series_data['Timeseries Parent']     = 'Category';
                $time_series_data['Timeseries Parent Key'] = $category->id;


                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;

                $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                $category->update_product_timeseries_record($object_timeseries, $date, $date);
             //   print $category->get('Code')."\n";


            }
        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
