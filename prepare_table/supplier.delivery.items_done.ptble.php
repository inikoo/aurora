<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2018 at 09:53:31 GMT+8, Kuala Lumpur

 Copyright (c) 2018, Inikoo

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
    $order = '`Purchase Order Transaction Fact Key`';
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
    = "`Part SKO Image Key`,`Part SKO Barcode`,`Supplier Delivery Units`,POTF.`Supplier Delivery Key`,`Part Reference`,P.`Part SKU`,`Supplier Delivery Checked Units`,`Part Package Description`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Placed Units`,`Metadata`,
`Purchase Order Transaction Fact Key`,POTF.`Supplier Part Key`,`Supplier Part Reference`,POTF.`Supplier Part Historic Key`,
`Supplier Part Description`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,
`Supplier Part Unit Cost`,`Part Package Weight`,POTF.`Supplier Delivery CBM`,POTF.`Supplier Delivery Weight`,`Supplier Key`,
`Supplier Delivery Net Amount`,`Currency Code`,

sum(`Supplier Delivery Placed SKOs`) as skos_in,
sum(`Supplier Delivery Net Amount`) as items_amount,
sum(`Supplier Delivery Extra Cost Amount`) as extra_amount,
sum(`Supplier Delivery Extra Cost Account Currency Amount`) as extra_amount_account_currency,

sum( `Supplier Delivery Extra Cost Account Currency Amount`+`Supplier Delivery Currency Exchange`*( `Supplier Delivery Net Amount`+`Supplier Delivery Extra Cost Amount` ) ) as paid_amount,

            

`Supplier Delivery Net Amount`


";


?>
