<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 11:49:05 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

//exit;


$group_by=' group by P.`Part SKU`';

$where  = sprintf(' where POTF.`Supplier Delivery Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'created') {
    $order = '`Order Date`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = 'ANY_VALUE(`Purchase Order Transaction Fact Key`)';
}

$table
    = "
  `Purchase Order Transaction Fact` POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) 
 left join  `Supplier Delivery Dimension` SPD on (SPD.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)

";

$sql_totals
    = "select count(distinct  P.`Part SKU`) as num from $table $where";


$fields
    = "
    ANY_VALUE(`Part SKO Image Key`),ANY_VALUE(`Supplier Part Supplier Key`) as `Supplier Key`,
    ANY_VALUE(`Part SKO Barcode`),
    ANY_VALUE(`Supplier Delivery Units`),
    POTF.`Supplier Delivery Key`,
    `Part Reference`,P.`Part SKU`, 
    ANY_VALUE(`Supplier Delivery Checked Units`) as `Supplier Delivery Checked Units`,
    ANY_VALUE(`Part Package Description`) as `Part Package Description`,
    ANY_VALUE(`Supplier Delivery Transaction Placed`),ANY_VALUE(`Supplier Delivery Placed Units`),ANY_VALUE(`Metadata`),
ANY_VALUE(`Purchase Order Transaction Fact Key`), ANY_VALUE(POTF.`Supplier Part Key`) as `Supplier Part Key`,ANY_VALUE(`Supplier Part Reference`) as `Supplier Part Reference` ,ANY_VALUE(POTF.`Supplier Part Historic Key`),
ANY_VALUE(`Supplier Part Description`),ANY_VALUE(`Part Units Per Package`),ANY_VALUE(`Supplier Part Packages Per Carton`),ANY_VALUE(`Supplier Part Carton CBM`),
ANY_VALUE(`Supplier Part Unit Cost`),ANY_VALUE(`Part Package Weight`),ANY_VALUE(POTF.`Supplier Delivery CBM`),ANY_VALUE(POTF.`Supplier Delivery Weight`),ANY_VALUE(`Purchase Order Submitted Units`) as `Purchase Order Submitted Units` ,ANY_VALUE(`Supplier Key`),
ANY_VALUE(`Supplier Delivery Net Amount`) as `Supplier Delivery Net Amount`,ANY_VALUE(`Currency Code`),

sum(`Supplier Delivery Placed SKOs`) as skos_in,
sum(`Supplier Delivery Net Amount`) as items_amount,
sum(`Supplier Delivery Extra Cost Amount`) as extra_amount,
sum(`Supplier Delivery Extra Cost Account Currency Amount`) as extra_amount_account_currency,

sum( `Supplier Delivery Extra Cost Account Currency Amount`+`Supplier Delivery Currency Exchange`*( `Supplier Delivery Net Amount`+`Supplier Delivery Extra Cost Amount` ) ) as paid_amount

            



";


