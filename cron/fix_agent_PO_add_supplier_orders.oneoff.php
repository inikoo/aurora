<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 11:24:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
require_once 'class.Agent.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (fix missing supplier orders)'
);



$sql = sprintf('SELECT `Purchase Order Key` FROM `Purchase Order Dimension` where `Purchase Order Parent`="Agent" ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $po=get_object('Purchase Order',$row['Purchase Order Key']);
        $po->editor=$editor;
        $po->create_agent_supplier_purchase_orders();
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
