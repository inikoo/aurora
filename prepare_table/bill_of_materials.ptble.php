<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 January 2019 at 14:03:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$table
       = "`Bill of Materials Bridge`   left join `Part Dimension` P on (P.`Part SKU`=`Bill of Materials Supplier Part Component Key`)   ";

$fields
    = '`Part SKU`,`Part Cost in Warehouse`,`Part Status`,`Part Reference`,`Part Unit Description`,`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Current On Hand Stock`,`Bill of Materials Quantity`,`Part Units per Package`
';

$filter_msg = '';
$filter_msg = '';
$wheref     = '';


$where = sprintf(
    " where  `Bill of Materials Supplier Part Key`=%d", $parameters['parent_key']
);



if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Part Reference`';
}elseif ($order == 'stock') {
    $order = '`Part Current Stock`';
} else {

    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct `Part SKU`) as num from $table  $where  ";


//print $sql_totals;

?>
