<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 19:52:38 GMTT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
case 'year':
	$where=sprintf(" where  Year(`Timesheet Date`)=%d ", $parameters['parent_key']);
	break;
case 'week':
	$where=sprintf(" where  yearweek(`Timesheet Date`,3)=%d ", $parameters['parent_key']);
	break;
case 'month':
	$year=substr($parameters['parent_key'], 0, 4);
	$month=substr($parameters['parent_key'], 4, 2);
	$where=sprintf(" where  month(`Timesheet Date`)=%d and Year(`Timesheet Date`)=%d ", $month, $year);
	break;

default:
	exit('parent not suported '.$parameters['parent']);
	break;
}

$table='  `Timesheet Dimension`  ';


switch ($parameters['group_by']) {
case 'month':
	$group_by=' group by  Month(`Timesheet Date`) ';
	$sql_totals="select count(distinct Month(`Timesheet Date`)) as num from $table  $where  ";

	break;
case 'week':
	$group_by=' group by  WEEK(`Timesheet Date`,3) ';
	$sql_totals="select count(distinct WEEK(`Timesheet Date`,1)) as num from $table  $where  ";

	break;
case 'day':
	$group_by=' group by  `Timesheet Date` ';
	$sql_totals="select count(distinct `Timesheet Date`) as num from $table  $where  ";

	break;
default:
	exit('group not suported '.$parameters['group_by']);
	break;
}



$wheref='';


$_order=$order;
$_dir=$order_direction;


if ($order=='alias')
	$order='`Staff Alias`';

else
	$order='`Timesheet Date`';






$fields="
month(`Timesheet Date`) month,
year(`Timesheet Date`) year,
yearweek(`Timesheet Date`,3) yearweek,
week(`Timesheet Date`,3) week,
adddate(`Timesheet Date`, INTERVAL -WEEKDAY(`Timesheet Date`) DAY)  week_starting,
`Timesheet Date`,
count(distinct `Timesheet Key`) as timesheets,
count(distinct `Timesheet Staff Key`) as employees,
count(distinct `Timesheet Date`) as days

";

?>
