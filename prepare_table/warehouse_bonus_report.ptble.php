<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6th may 2021 21:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$where_interval_working_hours = '';


$where = " where `Inventory Transaction Type` = 'Sale' and `Role Code`='PICK'  ";


if (isset($parameters['period'])) {

    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval               = prepare_mysql_dates($from, $to, '`Date Packed`');
    $where                        .= $where_interval['mysql'];
    $where_interval_working_hours = prepare_mysql_dates($from, $to, '`Timesheet Date`', 'only dates')['mysql'];
    $where_interval_feedback      = prepare_mysql_dates($from, $to, 'ITF2.`Date Picked`')['mysql'];
}


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Staff Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;



if ($order == 'name') {
    $order = '`Staff Name`';
} elseif ($order == 'deliveries') {
    $order = 'deliveries';
} elseif ($order == 'dp' or $order == 'dp_percentage') {
    $order = 'dp';
} elseif ($order == 'hrs') {
    $order = 'hrs';
} elseif ($order == 'dp_per_hour') {
    $order = 'dp_per_hour';
} elseif ($order == 'picks') {
    $order = 'picks';
} elseif ($order == 'cartons') {
    $order = 'cartons';
} elseif ($order == 'bonus') {
    $order = 'bonus';
} else {

    $order = 'S.`Staff Key`';
}


$group_by = 'group by S.`Staff Key`';

$table = ' `Inventory Transaction Fact` ITF  left join 
`ITF Picking Band Bridge` on (`ITF Picking Band ITF Key`=ITF.`Inventory Transaction Key` ) left join 

       
        `Staff Dimension` S on (S.`Staff Key`=ITF.`Picker Key`  or S.`Staff Key`=ITF.`Packer Key`) left join  
        `Staff Role Bridge` SRB on (SRB.`Staff Key`=S.`Staff Key`)
        ';

$fields = "`Picker Key`,ITF.`Inventory Transaction Key`,
  count(distinct ITF.`Delivery Note Key`,`Part SKU`) as dp ,



`Staff Name`,S.`Staff Key`,@deliveries := count(distinct ITF.`Delivery Note Key`) as deliveries , 
@hrs :=  (select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Picker Key` $where_interval_working_hours ) as hrs,
    sum(`ITF Picking Band Amount`) as bonus,
         sum(`ITF Picking Band SKOs`) as picks,
    sum(`ITF Picking Band Cartons`) as cartons,`Warehouse Key`


";


$sql_totals = "select count(Distinct `Picker Key` )  as num from $table  $where  ";

