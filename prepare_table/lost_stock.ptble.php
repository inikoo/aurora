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


$where = " where  `Inventory Transaction Quantity`<0 ";


$_elements      = '';
$count_elements = 0;
foreach (
    $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
) {
    if ($_value['selected']) {
        $count_elements++;
        $_elements .= ','.prepare_mysql($_key);

    }
}
$_elements = preg_replace('/^\,/', '', $_elements);
if ($_elements == '') {
    $where .= ' and false';
} else {
    $where .= ' and `Inventory Transaction Type` in ('.$_elements.')';
}

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



if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
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


$sql_totals = "select count(*) as num from $table    ";

$fields = '`User Alias`,ITF.`User Key`,`Note`,`Inventory Transaction Type` as type , -1*`Inventory Transaction Amount` as value,-1*`Inventory Transaction Quantity` as stock ,`Date` date, `Inventory Transaction Key`,ITF.`Part SKU`,`Part Reference`,`Part Package Description` ';


?>
