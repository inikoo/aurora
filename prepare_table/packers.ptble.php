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
    $where_interval = prepare_mysql_dates($from, $to, '`Date Packed`');
    $where .= $where_interval['mysql'];
    $where_interval_working_hours = prepare_mysql_dates($from, $to, '`Timesheet Date`','only dates')['mysql'];

}

$total_dp=0;
$sql=sprintf("select count(`Delivery Note Key`)*sum(`Packed`) as dp from  `Inventory Transaction Fact` $where group by `Packer Key` ");

if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $total_dp+=$row['dp'];

        }
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


if($total_dp==0){
    $total_dp=1;
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
}elseif ($order == 'dp') {
    $order = 'dp';
}elseif ($order == 'dp_percentage') {
    $order = 'dp_percentage';
}elseif ($order == 'hrs') {
    $order = 'hrs';
}elseif ($order == 'dp_per_hour') {
    $order = 'dp_per_hour';
}else{

    $order='`Packer Key`';
}


$group_by
    = 'group by `Packer Key`';

$table = ' `Inventory Transaction Fact` ITF left join `Staff Dimension` S on (S.`Staff Key`=ITF.`Packer Key`) ';

$fields = "`Staff Name`,`Staff Key`,count(`Delivery Note Key`) as deliveries ,sum(`Packed`) as packed, count(`Delivery Note Key`)*sum(`Packed`) dp ,(count(`Delivery Note Key`)*sum(`Packed`))/$total_dp dp_percentage ,
(select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours ) as hrs,
count(`Delivery Note Key`)*sum(`Packed`)/(select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours ) dp_per_hour

";


$sql_totals = "select count(Distinct `Packer Key` )  as num from $table  $where  ";




?>
