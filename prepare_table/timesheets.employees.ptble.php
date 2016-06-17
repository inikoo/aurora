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


elseif ($order=='staff_formatted_id')
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
	
elseif ($order=='worked_time_monday' )
	$order='worked_time_monday';
elseif ($order=='worked_time_tuesday' )
	$order='worked_time_tuesday';
elseif ($order=='worked_time_wednesday' )
	$order='worked_time_wednesday';
elseif ($order=='worked_time_thursday' )
	$order='worked_time_thursday';
elseif ($order=='worked_time_friday' )
	$order='worked_time_friday';
elseif ($order=='worked_time_saturday' )
	$order='worked_time_saturday';
elseif ($order=='worked_time_sunday' )
	$order='worked_time_sunday';
elseif ($order=='worked_time_workweek' )
	$order='worked_time_workweek';
elseif ($order=='worked_time_weekend' )
	$order='worked_time_weekend';
	
elseif ($order=='clocked_time_monday' )
	$order='clocked_time_monday';
elseif ($order=='clocked_time_tuesday' )
	$order='clocked_time_tuesday';
elseif ($order=='clocked_time_wednesday' )
	$order='clocked_time_wednesday';
elseif ($order=='clocked_time_thursday' )
	$order='clocked_time_thursday';
elseif ($order=='clocked_time_friday' )
	$order='clocked_time_friday';
elseif ($order=='clocked_time_saturday' )
	$order='clocked_time_saturday';
elseif ($order=='clocked_time_sunday' )
	$order='clocked_time_sunday';
elseif ($order=='clocked_time_workweek' )
	$order='clocked_time_workweek';
elseif ($order=='clocked_time_weekend' )
	$order='clocked_time_weekend';	


elseif ($order=='unpaid_overtime_monday' )
	$order='unpaid_overtime_monday';
elseif ($order=='unpaid_overtime_tuesday' )
	$order='unpaid_overtime_tuesday';
elseif ($order=='unpaid_overtime_wednesday' )
	$order='unpaid_overtime_wednesday';
elseif ($order=='unpaid_overtime_thursday' )
	$order='unpaid_overtime_thursday';
elseif ($order=='unpaid_overtime_friday' )
	$order='unpaid_overtime_friday';
elseif ($order=='unpaid_overtime_saturday' )
	$order='unpaid_overtime_saturday';
elseif ($order=='unpaid_overtime_sunday' )
	$order='unpaid_overtime_sunday';
elseif ($order=='unpaid_overtime_workweek' )
	$order='unpaid_overtime_workweek';
elseif ($order=='unpaid_overtime_weekend' )
	$order='unpaid_overtime_weekend';	

elseif ($order=='paid_overtime_monday' )
	$order='paid_overtime_monday';
elseif ($order=='paid_overtime_tuesday' )
	$order='paid_overtime_tuesday';
elseif ($order=='paid_overtime_wednesday' )
	$order='paid_overtime_wednesday';
elseif ($order=='paid_overtime_thursday' )
	$order='paid_overtime_thursday';
elseif ($order=='paid_overtime_friday' )
	$order='paid_overtime_friday';
elseif ($order=='paid_overtime_saturday' )
	$order='paid_overtime_saturday';
elseif ($order=='paid_overtime_sunday' )
	$order='paid_overtime_sunday';
elseif ($order=='paid_overtime_workweek' )
	$order='paid_overtime_workweek';
elseif ($order=='paid_overtime_weekend' )
	$order='paid_overtime_weekend';	

elseif ($order=='work_time_monday' )
	$order='work_time_monday';
elseif ($order=='work_time_tuesday' )
	$order='work_time_tuesday';
elseif ($order=='work_time_wednesday' )
	$order='work_time_wednesday';
elseif ($order=='work_time_thursday' )
	$order='work_time_thursday';
elseif ($order=='work_time_friday' )
	$order='work_time_friday';
elseif ($order=='work_time_saturday' )
	$order='work_time_saturday';
elseif ($order=='work_time_sunday' )
	$order='work_time_sunday';
elseif ($order=='work_time_workweek' )
	$order='work_time_workweek';
elseif ($order=='work_time_weekend' )
	$order='work_time_weekend';	



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
sum(`Timesheet Monday Paid Overtime`+`Timesheet Monday Unpaid Overtime`+`Timesheet Monday Working Time`) worked_time_monday,
sum(`Timesheet Tuesday Paid Overtime`+`Timesheet Tuesday Unpaid Overtime`+`Timesheet Tuesday Working Time`) worked_time_tuesday,
sum(`Timesheet Wednesday Paid Overtime`+`Timesheet Wednesday Unpaid Overtime`+`Timesheet Wednesday Working Time`) worked_time_wednesday,
sum(`Timesheet Thursday Paid Overtime`+`Timesheet Thursday Unpaid Overtime`+`Timesheet Thursday Working Time`) worked_time_thursday,
sum(`Timesheet Friday Paid Overtime`+`Timesheet Friday Unpaid Overtime`+`Timesheet Friday Working Time`) worked_time_friday,
sum(`Timesheet Saturday Paid Overtime`+`Timesheet Saturday Unpaid Overtime`+`Timesheet Saturday Working Time`) worked_time_saturday,
sum(`Timesheet Sunday Paid Overtime`+`Timesheet Sunday Unpaid Overtime`+`Timesheet Sunday Working Time`) worked_time_sunday,
sum(`Timesheet Paid Overtime`+`Timesheet Unpaid Overtime`+`Timesheet Working Time`-(`Timesheet Saturday Paid Overtime`+`Timesheet Saturday Unpaid Overtime`+`Timesheet Saturday Working Time`+`Timesheet Sunday Paid Overtime`+`Timesheet Sunday Unpaid Overtime`+`Timesheet Sunday Working Time`)) worked_time_workweek,
sum(`Timesheet Saturday Paid Overtime`+`Timesheet Saturday Unpaid Overtime`+`Timesheet Saturday Working Time`+`Timesheet Sunday Paid Overtime`+`Timesheet Sunday Unpaid Overtime`+`Timesheet Sunday Working Time`) worked_time_weekend,



sum(`Timesheet Monday Clocked Time`) clocked_time_monday,
sum(`Timesheet Tuesday Clocked Time`) clocked_time_tuesday,
sum(`Timesheet Wednesday Clocked Time`) clocked_time_wednesday,
sum(`Timesheet Thursday Clocked Time`) clocked_time_thursday,
sum(`Timesheet Friday Clocked Time`) clocked_time_friday,
sum(`Timesheet Saturday Clocked Time`) clocked_time_saturday,
sum(`Timesheet Sunday Clocked Time`) clocked_time_sunday,
sum(`Timesheet Clocked Time`-(`Timesheet Saturday Clocked Time`+`Timesheet Sunday Clocked Time`)) clocked_time_workweek,
sum(`Timesheet Saturday Clocked Time`+`Timesheet Sunday Clocked Time`) clocked_time_weekend,

sum(`Timesheet Monday Unpaid Overtime`) unpaid_overtime_monday,
sum(`Timesheet Tuesday Unpaid Overtime`) unpaid_overtime_tuesday,
sum(`Timesheet Wednesday Unpaid Overtime`) unpaid_overtime_wednesday,
sum(`Timesheet Thursday Unpaid Overtime`) unpaid_overtime_thursday,
sum(`Timesheet Friday Unpaid Overtime`) unpaid_overtime_friday,
sum(`Timesheet Saturday Unpaid Overtime`) unpaid_overtime_saturday,
sum(`Timesheet Sunday Unpaid Overtime`) unpaid_overtime_sunday,
sum(`Timesheet Unpaid Overtime`-(`Timesheet Saturday Unpaid Overtime`+`Timesheet Sunday Unpaid Overtime`)) unpaid_overtime_workweek,
sum(`Timesheet Saturday Unpaid Overtime`+`Timesheet Sunday Unpaid Overtime`) unpaid_overtime_weekend,

sum(`Timesheet Monday Paid Overtime`) paid_overtime_monday,
sum(`Timesheet Tuesday Paid Overtime`) paid_overtime_tuesday,
sum(`Timesheet Wednesday Paid Overtime`) paid_overtime_wednesday,
sum(`Timesheet Thursday Paid Overtime`) paid_overtime_thursday,
sum(`Timesheet Friday Paid Overtime`) paid_overtime_friday,
sum(`Timesheet Saturday Paid Overtime`) paid_overtime_saturday,
sum(`Timesheet Sunday Paid Overtime`) paid_overtime_sunday,
sum(`Timesheet Paid Overtime`-(`Timesheet Saturday Paid Overtime`+`Timesheet Sunday Paid Overtime`)) paid_overtime_workweek,
sum(`Timesheet Saturday Paid Overtime`+`Timesheet Sunday Paid Overtime`) paid_overtime_weekend,

sum(`Timesheet Monday Working Time`) work_time_monday,
sum(`Timesheet Tuesday Working Time`) work_time_tuesday,
sum(`Timesheet Wednesday Working Time`) work_time_wednesday,
sum(`Timesheet Thursday Working Time`) work_time_thursday,
sum(`Timesheet Friday Working Time`) work_time_friday,
sum(`Timesheet Saturday Working Time`) work_time_saturday,
sum(`Timesheet Sunday Working Time`) work_time_sunday,
sum(`Timesheet Working Time`-(`Timesheet Saturday Working Time`+`Timesheet Sunday Working Time`)) work_time_workweek,
sum(`Timesheet Saturday Working Time`+`Timesheet Sunday Working Time`) work_time_weekend,



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
