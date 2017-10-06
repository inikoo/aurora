<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2017 at 17:28:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

if (function_exists('mysql_connect')) {


    $default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");
}

require_once 'utils/aes.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';
require_once 'class.Material.php';
require_once 'class.Page.php';

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
include_once 'utils/object_functions.php';
include_once 'class.PartLocation.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


$sql =
    'SELECT `Inventory Transaction Key`,`Location Key`,ITF.`Part SKU`,ITF.`Delivery Note Key`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Section` FROM `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE    ITF.`Delivery Note Key`>0 AND DN.`Delivery Note Key` IS NULL;';

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $part     = get_object('Part', $row['Part SKU']);
        $location = get_object('Location', $row['Location Key']);


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);


        $sql = sprintf('delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d', $row['Inventory Transaction Key']);



        $db->exec($sql);
        if ($part_location->ok) {
            $part_location->update_stock();
        } else {

            $part->update_stock();
            $location->update_stock_value();


            foreach (
                $part->get_production_suppliers('objects') as $production
            ) {
                $production->update_locations_with_errors();
            }


        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



$sql =
"select `Date`,`Inventory Transaction Key`,`Location Key`,ITF.`Part SKU`,ITF.`Delivery Note Key`,`Note`,`Amount In`,`Inventory Transaction Type`,`Inventory Transaction Section`,`Delivery Note State`,`Inventory Transaction Quantity` from `Inventory Transaction Fact` ITF left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) where  `Inventory Transaction Section`='OIP'  and `Delivery Note State`='Dispatched' ;";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $part     = get_object('Part', $row['Part SKU']);
        $location = get_object('Location', $row['Location Key']);


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);


        $sql = sprintf('update  `Inventory Transaction Fact` set `Date Shipped`=%s,`Inventory Transaction Type` = "Sale",`Inventory Transaction Section`="Out"   where `Inventory Transaction Key`=%d',
                       prepare_mysql($row['Date']),
                       $row['Inventory Transaction Key']);


        $db->exec($sql);
        if ($part_location->ok) {
            $part_location->update_stock();
        } else {

            $part->update_stock();
            $location->update_stock_value();


            foreach (
                $part->get_production_suppliers('objects') as $production
            ) {
                $production->update_locations_with_errors();
            }


        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
