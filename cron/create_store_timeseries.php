<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 11:58:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

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


stores();


function stores() {

    global $db, $editor, $timeseries;
    $sql = sprintf('SELECT `Store Key` FROM `Store Dimension` ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $store = new Store($row['Store Key']);

            $timeseries_data = $timeseries['Store'];

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']           = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $store->create_timeseries($time_series_data);

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


?>
