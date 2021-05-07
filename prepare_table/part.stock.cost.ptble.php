<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 11:49:54 GMT, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where      = "where true  ";
$table
            = "`Inventory Transaction Fact` ITF left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `Purchase Order Transaction Fact` POTF on (ITF.`Metadata`=POTF.`Purchase Order Transaction Fact Key`)
 left join `Supplier Delivery Dimension` SDD on (POTF.`Supplier Delivery Key`=SDD.`Supplier Delivery Key`)  
 ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';

if ($parameters['parent'] == 'part') {

  //  $where = sprintf(
  //      " where   ( `Inventory Transaction Type`='In' or ( `Inventory Transaction Type`='Adjust' and `Inventory Transaction Quantity`>0  and `Location Key`>1 )  )  and ITF.`Part SKU`=%d", $parameters['parent_key']
  //  );
    $where = sprintf(
        " where    `Inventory Transaction Type`='In'   and ITF.`Part SKU`=%d", $parameters['parent_key']
    );

} elseif ($parameters['parent'] == 'account') {


    $where = sprintf(" where  `Inventory Transaction Record Type`='Movement' ");


} elseif ($parameters['parent'] == 'location') {

    $where = sprintf(
        " where  `Inventory Transaction Record Type`='Movement' and ITF.`Location Key`=%d", $parameters['parent_key']
    );


} else {
    exit("parent not found ".$parameters['parent']);
}

if (isset($extra_where)) {
    $where .= $extra_where;
}



if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

$order_direction = '';

$order = '`Date` desc  ,`Inventory Transaction Key` desc ';


$sql_totals
    = "select count(Distinct `Inventory Transaction Key`) as num from $table  $where  ";



$fields
    .= '`Date`,`Inventory Transaction Section`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,`Warehouse Key`,`Running Stock`,(select  `ITF POTF Costing Done ITF Key`  from `ITF POTF Costing Done Bridge`  where `ITF POTF Costing Done ITF Key`=`Inventory Transaction Key` ) as costing_done,
`Part Reference`,ITF.`Part SKU`,ITF.`Delivery Note Key`,ITF.`Location Key`,`Part Location Stock`,`Inventory Transaction Type`,ITF.`Metadata`,
`Note`,ITF.`User Key`,`Supplier Delivery Public ID`,POTF.`Supplier Delivery Key`,POTF.`Purchase Order Transaction Fact Key`,`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,`Inventory Transaction Amount`
';




