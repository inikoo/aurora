<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Data_Sets.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';

require_once 'utils/date_functions.php';
require_once 'conf/data_sets.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

foreach ($data_sets as $data_set_data) {
    $data_set_data['editor'] = $editor;

    $data_set = $account->create_data_sets($data_set_data);
    $data_set->update_stats();
}


?>
