<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 11:12:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$table = "`Inventory Transaction Fact` ITF  left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`) ";

$group_by = '  ';


$wheref = '';


$where = " where `Inventory Transaction Type` in ('Lost','Broken','Other Out') and `Inventory Transaction Quantity`<0 ";

if (isset($parameters['period'])) {

    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Date`');
    $where          .= $where_interval['mysql'];

}


$_order = $order;
$_dir   = $order_direction;



if ($order == 'date') {
    $order = '`Date`';
}elseif ($order == 'staff') {
    $order = '`User Alias`';
}elseif ($order == 'reference') {
    $order = '`Part Reference`';
}elseif ($order == 'note') {
    $order = '`Note`';
}elseif ($order == 'value') {
    $order = ' value  ';
}elseif ($order == 'stock') {
    $order = 'stock';
}elseif ($order == 'type') {
    $order = 'type';
}else{

    $order='`Inventory Transaction Key`';
}

$order = '`Date`';


$sql_totals = "select count(*) as num from $table  $where  ";

$fields = '`User Alias`,ITF.`User Key`,`Note`,`Inventory Transaction Type` as type , -1*`Inventory Transaction Amount` as value,-1*`Inventory Transaction Quantity` as stock ,`Date` date, `Inventory Transaction Key`,ITF.`Part SKU`,`Part Reference`,`Part Package Description` ';


?>
