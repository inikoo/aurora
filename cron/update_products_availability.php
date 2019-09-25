<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 19:45:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'class.Product.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (product availability)'
);

$sql = sprintf(
    "SELECT `Product ID` FROM `Product Dimension` order by `Product ID`  desc "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $product         = new Product($row['Product ID']);
        $product->editor = $editor;
        $web_state_old   = $product->get_web_state();
        $product->update_availability();
        $web_state_new = $product->get_web_state();

        print "$product->id\r";

        if ($web_state_old != $web_state_new) {
            print $product->get('Store Key').' '.$product->get('Code')." $web_state_old $web_state_new \n";
        }


    }

}



