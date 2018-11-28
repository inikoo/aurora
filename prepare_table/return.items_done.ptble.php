<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 November 2018 at 21:52:53 GMT+8, Kuala Lumpur

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

//exit;


$group_by=' group by P.`Part SKU`';

$where  = sprintf(' where POTF.`Supplier Delivery Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'referenc' and $f_value != '') {
    $wheref .= " and `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'part_reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
}elseif ($order == 'received_quantity') {
    $order = 'skos_in';
}elseif ($order == 'checked_quantity') {
    $order = 'checked_quantity';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = '`Purchase Order Transaction Fact Key`';
}

$table
    = "
  `Purchase Order Transaction Fact` POTF
 left join  `Part Dimension` P on (P.`Part SKU`=POTF.`Purchase Order Transaction Part SKU`) 
 left join  `Supplier Delivery Dimension` SPD on (SPD.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)

";

$sql_totals
    = "select count(distinct  P.`Part SKU`) as num from $table $where";


$fields
    = "`Part SKO Image Key`,`Part SKO Barcode`,`Supplier Delivery Units`,POTF.`Supplier Delivery Key`,`Part Reference`,P.`Part SKU`,`Supplier Delivery Checked Units`,`Part Package Description`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Placed Units`,`Metadata`,
`Purchase Order Transaction Fact Key`,
`Part Units Per Package`,
`Part Package Weight`,POTF.`Supplier Delivery CBM`,POTF.`Supplier Delivery Weight`,`Supplier Key`,
`Supplier Delivery Net Amount`,`Currency Code`,
sum(`Supplier Delivery Checked Units`*`Part Units Per Package`) as checked_quantity,

sum(`Supplier Delivery Placed SKOs`) as skos_in,
sum(`Supplier Delivery Net Amount`) as items_amount,
sum(`Supplier Delivery Extra Cost Amount`) as extra_amount,
sum(`Supplier Delivery Extra Cost Account Currency Amount`) as extra_amount_account_currency,

sum( `Supplier Delivery Extra Cost Account Currency Amount`+`Supplier Delivery Currency Exchange`*( `Supplier Delivery Net Amount`+`Supplier Delivery Extra Cost Amount` ) ) as paid_amount,

            

`Supplier Delivery Net Amount`


";


?>
