<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 April 2016 at 14:45:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where = "where true  ";
$table = "`Inventory Spanshot Fact` ISF";


if ($parameters['frequency'] == 'annually') {
    $group_by          = ' group by Year(`Date`) ';
    $sql_totals_fields = 'Year(`Date`)';
} elseif ($parameters['frequency'] == 'monthly') {
    $group_by          = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'weekly') {
    $group_by          = ' group by Yearweek(`Date`,3) ';
    $sql_totals_fields = 'Yearweek(`Date`,3)';
} elseif ($parameters['frequency'] == 'daily') {
    $group_by          = ' group by `Date` ';
    $sql_totals_fields = '`Date`';
}

$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';

if ($parameters['parent'] == 'part') {

    $where     = sprintf(
        " where   ISF.`Part SKU`=%d", $parameters['parent_key']
    );
    $where_sub = sprintf(
        " where `Part SKU`=%d and OISF.`Date`=ISF.`Date`", $parameters['parent_key']
    );


} elseif ($parameters['parent'] == 'account') {

    $where_sub = sprintf(" where  true");


} else {
    exit("parent not found: ".$parameters['parent']);
}

if (isset($extra_where)) {
    $where .= $extra_where;
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'stock') {
    $order = '`Part Current Stock`';
} else {

    $order = '`Date`';
}


$sql_totals
    = "select count(Distinct $sql_totals_fields) as num from $table  $where  ";
$fields .= '`Date`';



if($account->get('Account Add Stock Value Type')=='Blockchain'){
    $fields
        = "`Date`,
( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF  $where_sub)as `Quantity On Hand`,
	( select  sum(`Value At Cost`) from `Inventory Spanshot Fact` OISF  $where_sub   )as `Stock Value`,
	sum(`Quantity Sold`) as `Quantity Sold`,
	sum(`Quantity In`) as `Quantity In`,
	sum(`Quantity Lost`) as `Quantity Lost` 

";
}else{
    $fields
        = "`Date`,
( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF  $where_sub)as `Quantity On Hand`,
	( select  sum(`Value At Day Cost`) from `Inventory Spanshot Fact` OISF  $where_sub   )as `Stock Value`,
	sum(`Quantity Sold`) as `Quantity Sold`,
	sum(`Quantity In`) as `Quantity In`,
	sum(`Quantity Lost`) as `Quantity Lost` 

";
}




?>
