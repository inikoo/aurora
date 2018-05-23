<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 12:22:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$table = "`Inventory Warehouse Spanshot Fact` ";


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

if ($parameters['parent'] == 'warehouse') {
    $where = sprintf(" where `Warehouse Key`=%d", $parameters['parent_key']);
} elseif ($parameters['parent'] == 'account') {
    $where = sprintf(" where  true");
} else {
    exit("parent not found: ".$parameters['parent']);
}

if (isset($extra_where)) {
    $where .= $extra_where;
}


$_order = $order;
$_dir   = $order_direction;


$order = '`Date`';


$sql_totals
    = "select count(Distinct $sql_totals_fields) as num from $table  $where  ";



if($account->get('Account Add Stock Value Type')=='Blockchain'){
    $fields = "`Date`,`Parts`,`Locations`,`Value At Cost`,`Value At Day Cost`,`Value Commercial`,`Value At Cost` as Value  ";
}else{
    $fields = "`Date`,`Parts`,`Locations`,`Value At Cost`,`Value At Day Cost`,`Value Commercial`,`Value At Day Cost` as Value  ";
}




?>
