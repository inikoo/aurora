<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2018 at 22:48:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

//exit;
$where  = sprintf(' where POTF.`Supplier Delivery Key`=%d  and `Supplier Delivery Checked Units` is not null and (`Supplier Delivery Checked Units`!=`Supplier Delivery Units`) ', $parameters['parent_key']
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

";

$sql_totals
    = "select count(distinct  `Purchase Order Transaction Fact Key`) as num from $table $where";


$locations
    = "
, IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data
";

$fields
    = "`Part Recommended Product Unit Name`,`Supplier Part Supplier Key`,`Part SKO Image Key`,`Part SKO Barcode`,`Supplier Delivery Units`,`Supplier Delivery Key`,`Part Reference`,P.`Part SKU`,`Supplier Delivery Checked Units`,`Part Package Description`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Placed Units`,`Metadata`,
`Purchase Order Transaction Fact Key`,POTF.`Supplier Part Key`,`Supplier Part Reference`,POTF.`Supplier Part Historic Key`,`Part Package Description`
`Supplier Part Description`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,
`Supplier Part Unit Cost`,`Part Package Weight`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,`Supplier Key`,
`Supplier Delivery Net Amount`,`Currency Code` $locations

";



