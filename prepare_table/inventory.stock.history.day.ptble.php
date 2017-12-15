<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 December 2017 at 09:35:58 GMT, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

$table = "`Inventory Spanshot Fact` ISF  left join `Part Dimension` P on (ISF.`Part SKU`=P.`Part SKU`) ";

 $group_by          = ' group by ISF.`Part SKU` ';
    


$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';

if(!$parameters['warehouse_key']){
    $parameters['warehouse_key']=1;
}


if ($parameters['parent'] == 'day') {
    $where = sprintf(" where `Warehouse Key`=%d  and `Date`=%s ", $parameters['warehouse_key'],prepare_mysql($parameters['parent_key']));
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
    = "select count(Distinct ISF.`Part SKU`) as num from $table  $where  ";

$fields
    = "ISF.`Part SKU`,`Part Reference`,`Part Package Description`,sum(`Quantity On Hand`) as stock,sum(`Quantity Sold`) as sold,sum(`Quantity Lost`) as lost,sum(`Quantity On Hand`) as stock,sum(`Quantity Given`) as given,sum(`Quantity In`) as book_in,sum(`Value At Cost`) as stock_value";


?>
