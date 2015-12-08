<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2015 at 15:58:19 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {

case 'year':
	$where=sprintf(" where  Year(`Timesheet Date`)=%d ", $parameters['parent_key']);
	break;
case 'month':
	$year=substr($parameters['parent_key'], 0, 4);
	$month=substr($parameters['parent_key'], 4, 2);
	$where=sprintf(" where  month(`Timesheet Date`)=%d and Year(`Timesheet Date`)=%d ", $month, $year);
	break;	
case 'week':
	$where=sprintf(" where  yearweek(`Timesheet Date`,3)=%d ", $parameters['parent_key']);
	break;
case 'day':
	$where=sprintf(" where  `Timesheet Date`=%s ", prepare_mysql($parameters['parent_key']));
	break;
default:
	exit('parent not suported '.$parameters['parent']);
	break;
}

$group_by=' group by `Timesheet Staff Key` ';





$wheref='';
if ($parameters['f_field']=='alias' and $f_value!=''  ) {
	$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref=sprintf('  and  `Staff Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}



$_order=$order;
$_dir=$order_direction;


if ($order=='alias') {
	$order="`Staff Alias` $order_direction , `Timesheet Date`";

	$order_direction='';
}elseif ($order=='name') {
	$order="`Staff Name` $order_direction , `Timesheet Date`";

	$order_direction='';

}elseif ($order=='payroll_id')
	$order='`Staff ID`';


elseif ($order=='staff_formated_id')
	$order='`Timesheet Staff Key`';
elseif ($order=='date' or $order=='time')
	$order='`Timesheet Date`';
elseif ($order=='days' )
	$order='days';
elseif ($order=='clocked_time' )
	$order='clocked_time';
elseif ($order=='breaks' )
	$order='breaks';
elseif ($order=='work_time' )
	$order='work_time';
elseif ($order=='unpaid_overtime' )
	$order='unpaid_overtime';
elseif ($order=='paid_overtime' )
	$order='paid_overtime';
elseif ($order=='worked_time' )
	$order='worked_time';



elseif ($order=='clocking_records' )
	$order='clocking_records';
else
	$order='`Timesheet Key`';



$table='  `Timesheet Dimension` as TD left join `Staff Dimension` SD on (SD.`Staff Key`=TD.`Timesheet Staff Key`) ';

$sql_totals="select count(distinct `Timesheet Staff Key`) as num from $table  $where  ";

//print $sql_totals;
$fields="
sum(`Timesheet Paid Overtime`+`Timesheet Unpaid Overtime`+`Timesheet Working Time`)  worked_time,
sum(`Timesheet Paid Overtime`) paid_overtime,
sum(`Timesheet Unpaid Overtime`) unpaid_overtime,
sum(`Timesheet Working Time`) work_time,
sum(`Timesheet Breaks Time`) breaks,



sum(`Timesheet Clocking Records`) clocking_records,
sum(`Timesheet Clocked Time`) clocked_time,
count(distinct `Timesheet Date`) days,

sum(`Timesheet Ignored Clocking Records`) clocking_ignored_records,
`Staff Alias`,
`Timesheet Staff Key`,
`Staff Name`,
`Timesheet Date`,`Staff ID`
";

?>
