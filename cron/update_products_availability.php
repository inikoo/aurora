<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 19:45:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


require_once 'class.Product.php';
require_once 'class.Category.php';


$editor = array(
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (product availability)'
);

$where = ' where `Product Store Key`=19 ';
//$where = ' where `Product ID`=226780 ';
$where = ' where ( `Product Availability`>0   and  `Product Availability`<2 )  and `Product Store Key` NOT IN (19,25,24,22) ';

//$where = '';
$total = 0;
$sql   = "SELECT count(*) AS num FROM `Product Dimension` $where ";
/** @var PDO $db */
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    }
}
$print_est = true;
$lap_time0 = date('U');
$contador  = 0;


$sql = "SELECT `Product ID` FROM `Product Dimension` $where order by `Product ID`  desc ";
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $contador++;

        $product         = new Product($row['Product ID']);
        $product->editor = $editor;
        $product->update_availability(false);

        print $contador.'/ '.$total.'  '.$product->get('ID')." ".$product->get('Code')."  \n";


    }

}



