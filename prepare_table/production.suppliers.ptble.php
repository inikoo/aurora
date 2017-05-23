<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2016 at 16:42:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$currency   = '';
$where      = 'where true';
$table
            = '`Supplier Production Dimension` PS left join   `Supplier Dimension` S  on (S.`Supplier Key`=`Supplier Production Supplier Key`) left join `Supplier Data`  D on (S.`Supplier Key`=D.`Supplier Key`)';
$group_by   = '';
$where_type = '';


$where = sprintf(" where true ");


$filter_msg = '';
$wheref     = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Code` like '".addslashes($f_value)."%'";
}
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Supplier Name` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'low' and is_numeric($f_value)) {
    $wheref .= " and lowstock>=$f_value  ";
} elseif ($parameters['f_field'] == 'outofstock' and is_numeric($f_value)) {
    $wheref .= " and outofstock>=$f_value  ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Supplier Code`';
} elseif ($order == 'name') {
    $order = '`Supplier Name`';
} elseif ($order == 'location') {
    $order = '`Supplier Location`';
} elseif ($order == 'email') {
    $order = '`Supplier Main XHTML Email`';
} elseif ($order == 'telephone') {
    $order = '`Supplier Preferred Contact Number Formatted Number`';
} elseif ($order == 'contact') {
    $order = "`Supplier Main Contact Name`";
} elseif ($order == 'company') {
    $order = "`Supplier Company Name`";
} elseif ($order == 'supplier_parts') {
    $order = '`Supplier Number Parts`';
} elseif ($order == 'active_supplier_parts') {
    $order = '`Supplier Number Active Parts`';
} elseif ($order == 'pending_pos') {
    $order = '`Supplier Number Open Purchase Orders`';
} elseif ($order == 'origin') {
    $order = "`Supplier Products Origin Country Code`";
} elseif ($order == 'delivery_time') {
    $order = "`Supplier Average Delivery Days`";
} elseif ($order == 'low') {
    $order = "`Supplier Number Low Parts`";
} elseif ($order == 'surplus') {
    $order = "`Supplier Number Surplus Parts`";
} elseif ($order == 'optimal') {
    $order = "`Supplier Number Optimal Parts`";
} elseif ($order == 'low') {
    $order = "`Supplier Number Low Parts`";
} elseif ($order == 'critical') {
    $order = "`Supplier Number Critical Parts`";
} elseif ($order == 'out_of_stock') {
    $order = "`Supplier Number Out Of Stock Parts`";
} else {
    $order = "S.`Supplier Key`";
}

$sql_totals
    = "select count(Distinct S.`Supplier Key`) as num from $table  $where  $where_type";
$fields
    = "
S.`Supplier Key`,`Supplier Code`,`Supplier Name`,`Supplier Number Active Parts`,
`Supplier Location`,`Supplier Main Plain Email`,`Supplier Preferred Contact Number`,`Supplier Preferred Contact Number Formatted Number`,`Supplier Main Contact Name`,`Supplier Company Name`,
`Supplier Number Parts`,`Supplier Number Surplus Parts`,`Supplier Number Optimal Parts`,`Supplier Number Low Parts`,`Supplier Number Critical Parts`,`Supplier Number Critical Parts`,`Supplier Number Out Of Stock Parts`
";
/*
`Supplier Products Origin Country Code`,`Supplier $db_period Acc Parts Sold Amount`,`Supplier $db_period Acc 1YB Parts Sold Amount`,
`Supplier $db_period Acc Parts Profit`,`Supplier $db_period Acc Parts Profit After Storing`,`Supplier $db_period Acc Parts Cost`,`Supplier $db_period Acc Parts Sold`,`Supplier $db_period Acc Parts Required`,`Supplier $db_period Acc Parts Margin`,

`Supplier Average Delivery Days`,`Supplier Number Open Purchase Orders`,
`Supplier 1 Year Ago Sales Amount`,`Supplier 2 Year Ago Sales Amount`,`Supplier 3 Year Ago Sales Amount`,`Supplier 4 Year Ago Sales Amount`,
*/

?>
