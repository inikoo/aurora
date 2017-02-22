<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';
require_once 'class.Category.php';
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

$timeseries=get_time_series_config();


suppliers();


function suppliers() {

    global $db, $editor, $timeseries;

    $sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension` order by `Supplier Code` ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $supplier = new Supplier($row['Supplier Key']);


            $timeseries_data = $timeseries['Supplier'];

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']           = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $supplier->create_timeseries($time_series_data);
                print $supplier->get('Code')."\n";

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit($sql);
    }


}


?>
