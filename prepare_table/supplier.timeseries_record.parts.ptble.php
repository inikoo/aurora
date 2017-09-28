<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 21:34:33 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$group_by = '';

$table = sprintf(
    '  `Timeseries Record Drill Down` TRDD  left join   `Part Dimension` P   on (TRDD.`Timeseries Record Drill Down Subject Key`=P.`Part SKU` )  '
);


$where = sprintf(
    "where  `Timeseries Record Drill Down Subject`='Part' and `Timeseries Record Drill Down Timeseries Record Key`=%d  ", $parameters['parent_key']
);


$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
}  elseif ($order == 'dispatched') {
    $order = '`Timeseries Record Drill Down Integer A`';
}  elseif ($order == 'deliveries') {
    $order = '`Timeseries Record Drill Down Integer B`';
} elseif ($order == 'sales') {
    $order = '`Timeseries Record Drill Down Float A`';
}elseif ($order == 'delta_sales_percentage') {
    $order= "((`Timeseries Record Drill Down Float A`-`Timeseries Record Drill Down Float C`)/`Timeseries Record Drill Down Float C`)";
}elseif ($order == 'delta_sales') {
    $order = '`Timeseries Record Drill Down Float A`-`Timeseries Record Drill Down Float C`';
}else {

    $order = '`Timeseries Record Drill Down Timeseries Record Key`';
}


$sql_totals = "select count(Distinct TRDD.`Timeseries Record Drill Down Subject Key`) as num from $table  $where  ";



$fields .= "
`Part Reference`,`Part Package Description`,`Part SKU`,
`Timeseries Record Drill Down Float A`,`Timeseries Record Drill Down Float B`,`Timeseries Record Drill Down Float C`,`Timeseries Record Drill Down Float D`,
`Timeseries Record Drill Down Integer A`,`Timeseries Record Drill Down Integer B`,`Timeseries Record Drill Down Integer C`,`Timeseries Record Drill Down Integer D`

";
//min(`Order Date`) as date,sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio`) as required


?>
