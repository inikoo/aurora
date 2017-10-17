<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 17 October 2017 at 17:36:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = "where `Part Barcode Number Error` is not null ";
$table  = "`Part Dimension` P left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';
$group_by     = '';


if ($parameters['f_field'] == 'used_in' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Used In` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'supplied_by' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Supplied By` like '%".addslashes(
            $f_value
        )."%'";
}  elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
} elseif ($order == 'barcode') {
    $order = '`Part Barcode Number`';
} elseif ($order == 'error') {
    $order = '`Part Barcode Number Error`';
} elseif ($order == 'status') {
    $order = '`Part Status`';
}  else {

    $order = '`Part SKU`';
}

$sql_totals = "select count(*) as num from $table  $where  ";
//print $sql_totals;

$fields= "P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Barcode Number`,`Part Barcode Number Error`,`Part Status`";



?>
