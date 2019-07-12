<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 December 2017 at 09:35:58 GMT, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

$table = "`Inventory Spanshot Fact` ISF  left join `Part Dimension` P on (ISF.`Part SKU`=P.`Part SKU`) ";

$group_by = ' group by ISF.`Part SKU` ';


$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';

// todo change when multiwarehouses add  `Warehouse Key`=%d  and

//if (!$parameters['warehouse_key']) {
//    $parameters['warehouse_key'] = 1;
//}


if ($parameters['parent'] == 'day') {
    $where = sprintf(" where `Date`=%s ", prepare_mysql($parameters['parent_key']));
} else {
    exit("parent not found: ".$parameters['parent']);
}

if (isset($extra_where)) {
    $where .= $extra_where;
}


$_order = $order;
$_dir   = $order_direction;

//print $order;


if ($order == 'stock_value') {
    $order = 'stock_value';
} elseif ($order == 'stock') {
    $order = 'stock';
} elseif ($order == 'cost') {
    $order = 'cost';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
} elseif ($order == 'in') {
    $order = 'book_in';
} elseif ($order == 'sold') {
    $order = 'sold';
} elseif ($order == 'lost') {
    $order = 'lost';
} elseif ($order == 'given') {
    $order = 'given';
} elseif ($order == 'stock_left_1_year_ago') {
    $order = 'stock_left_1_year_ago';
}elseif ($order == 'no_sales_1_year') {
    $order = '`Dormant 1 Year`';
}else {
    $order = '`Date`';

}


$sql_totals = "select count(Distinct ISF.`Part SKU`) as num from $table  $where  ";


$fields = "ISF.`Part SKU`,`Part Reference`,`Part Package Description`,sum(`Quantity On Hand`) as stock,sum(`Quantity Sold`) as sold,sum(`Quantity Lost`) as lost,sum(`Quantity On Hand`) as stock,sum(`Quantity Given`) as given,sum(`Quantity In`) as book_in,
    sum(`Value At Cost`) as stock_value, `Inventory Spanshot Warehouse SKO Value` as cost,
     sum(`Inventory Spanshot Stock Left 1 Year Ago`) stock_left_1_year_ago,`Dormant 1 Year` as no_sales_1_year,`Part Valid From`
     ";





