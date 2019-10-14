<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 11:27:38 GMT+8, Sanur, Bal, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf("SELECT `Purchase Order Key` FROM `Purchase Order Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $po = get_object('PurchaseOrder', $row['Purchase Order Key']);

        $po->update_totals();


    }

}

