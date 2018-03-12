<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2018 at 22:43:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/



$where_interval_working_hours='';


$where = " where `Inventory Transaction Type` = 'Sale'   ";


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Date`');
    $where .= $where_interval['mysql'];
    $where_interval_working_hours = prepare_mysql_dates($from, $to, '`Timesheet Date`','only dates')['mysql'];

}









$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Staff Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Staff Name`';
}elseif ($order == 'packed') {
    $order = 'packed';
}elseif ($order == 'deliveries') {
    $order = 'deliveries';
}elseif ($order == 'dp' or $order == 'dp_percentage') {
    $order = 'dp';
}elseif ($order == 'hrs') {
    $order = 'hrs';
}elseif ($order == 'dp_per_hour') {
    $order = 'dp_per_hour';
}elseif ($order == 'deliveries_with_errors') {
    $order = 'deliveries_with_errors';
}elseif ($order == 'deliveries_with_errors_percentage') {
    $order = ' ( count(distinct OPTD.`Packer Fault Delivery Note Key`)/count(distinct ITF.`Delivery Note Key`))  ';
}elseif ($order == 'picks_with_errors') {
    $order = 'picks_with_errors';
}elseif ($order == 'picks_with_errors_percentage') {
    $order = ' picks_with_errors_percentage ';
}else{

    $order='`Packer Key`';
}


$group_by
    = 'group by `Packer Key`';

$table = ' `Inventory Transaction Fact` ITF  left join `Order Post Transaction Dimension` OPTD on (ITF.`Inventory Transaction Key`=OPTD.`Order Transaction Fact Key`) left join `Staff Dimension` S on (S.`Staff Key`=ITF.`Packer Key`) ';

$fields = "`Staff Name`,`Staff Key`,@deliveries := count(distinct ITF.`Delivery Note Key`) as deliveries , sum(`Packed`) as packed, @dp :=  count(distinct ITF.`Delivery Note Key`,`Part SKU`) as dp ,
@hrs :=  (select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours ) as hrs,
  count(distinct ITF.`Delivery Note Key`,`Part SKU`)/ (select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours )  as dp_per_hour,
   @deliveries_with_error := count(distinct OPTD.`Packer Fault Delivery Note Key`) as deliveries_with_errors,
   sum( OPTD.`Packer Fault`) as picks_with_errors,
    sum( OPTD.`Packer Fault`)/count(*) as picks_with_errors_percentage

";


$sql_totals = "select count(Distinct `Packer Key` )  as num from $table  $where  ";


?>
