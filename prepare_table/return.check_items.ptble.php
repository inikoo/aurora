<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 November 2018 at 13:31:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

//exit;
$where  = sprintf(' where POTF.`Supplier Delivery Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'reference' and $f_value != '') {
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

 left join  `Part Dimension` P on (P.`Part SKU`=POTF.`Purchase Order Transaction Part SKU`)

";

$sql_totals
    = "select count(distinct  `Purchase Order Transaction Fact Key`) as num from $table $where";


$locations
    = "
, IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data
";

$fields
    = "`Purchase Order Transaction Part SKU`,`Part Recommended Product Unit Name`,`Part SKO Image Key`,`Part SKO Barcode`,`Supplier Delivery Units`,`Supplier Delivery Key`,`Part Reference`,P.`Part SKU`,`Supplier Delivery Checked Units`,`Part Package Description`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Placed Units`,`Metadata`,
`Purchase Order Transaction Fact Key`,`Part Units Per Package`,`Part Package Weight`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,`Supplier Key`,`Purchase Order Submitted Unit Cost`,`Purchase Order Submitted Unit Extra Cost Percentage`,
`Supplier Delivery Net Amount`,`Currency Code` $locations

";


?>
