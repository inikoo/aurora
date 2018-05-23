<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 19:02:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$table = "`Inventory Transaction Fact` ITF  left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `Delivery Note Dimension` DN on (ITF.`Delivery Note Key`=DN.`Delivery Note Key`) ";

$group_by = '  ';


$wheref = '';


$where = " where  `Amount In`=0 and `Inventory Transaction Type`='Sale' ";


if (isset($parameters['elements'])) {
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
    } elseif ($count_elements < 2) {
        $where .= ' and `Delivery Note Type` in ('.$_elements.')';
    }
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
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
}elseif ($order == 'delivery_note') {
    $order = '`Delivery Note ID`';
} elseif ($order == 'note') {
    $order = '`Note`';
} elseif ($order == 'value') {
    $order = ' value  ';
} elseif ($order == 'stock') {
    $order = 'stock';
} elseif ($order == 'type') {
    $order = 'type';
} else {

    $order = '`Inventory Transaction Key`';
}

$order = '`Date`';


$sql_totals = "select count(*) as num from $table  $where $wheref ";

$fields =
    '`Delivery Note Store Key`,`Delivery Note Type` as type,`Delivery Note ID`,ITF.`Delivery Note Key`,`Note` , -1*`Inventory Transaction Amount` as value,-1*`Inventory Transaction Quantity` as stock ,`Date` date, `Inventory Transaction Key`,ITF.`Part SKU`,`Part Reference`,`Part Package Description` ';


?>
