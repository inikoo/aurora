<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished:22 November 2015 at 21:37:03 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
case 'employee':
	$where=sprintf(" where  TD.`Timesheet Staff Key`=%d ", $parameters['parent_key']);
	break;

case 'account':
	$where=sprintf(" where true ");
	break;
default:
	exit('parent not suported');
	break;
}


if (isset($parameters['period'])) {


    include_once 'utils/date_functions.php';


	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($parameters['period'], $parameters['from'], $parameters['to']);



	$where_interval=prepare_mysql_dates($from, $to, '`Timesheet Date`');
	
	
	
	$where.=preg_replace('/ \d{2}:\d{2}:\d{2}/','',$where_interval['mysql']);
	
//	print " $from, $to $where\n";

}




$wheref='';
if ($parameters['f_field']=='alias' and $f_value!=''  ) {
	$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref=sprintf('  and  `Staff Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}
/*
	'id'=>(integer) $data['Timesheet Key'],
			'staff_key'=>(integer) $data['Timesheet Staff Key'],
			'formated_id'=>sprintf("%05d", $data['Timesheet Key']),

			'staff_formated_id'=>sprintf("%04d", $data['Timesheet Staff Key']),
			'alias'=>$data['Staff Alias'],
			'name'=>$data['Staff Name'],
			'payroll_id'=>$data['Staff ID'],
			'date'=>($data['Timesheet Date']!=''?strftime("%a %e %b %Y", strtotime($data['Timesheet Date'])):''),
			'clocked_hours'=>number($data['Timesheet Clocked Hours'],2).' '._('hours'),
			'clocking_records'=>number($data['Timesheet Clocking Records'])
*/


$_order=$order;
$_dir=$order_direction;


if ($order=='alias')
	$order='`Staff Alias`';
elseif ($order=='name')
	$order='`Staff Name`';
elseif ($order=='payroll_id')
	$order='`Staff ID`';	
	
elseif ($order=='clocked_hours')
	$order='`Timesheet Clocked Time`';
elseif ($order=='clocking_records')
	$order='`Timesheet Clocking Records`';		
elseif ($order=='staff_formated_id')
	$order='`Timesheet Staff Key`';	
elseif ($order=='date' or $order=='time')
	$order='`Timesheet Date`';

else
	$order='`Timesheet Key`';




$table='  `Timesheet Dimension` as TD left join `Staff Dimension` SD on (SD.`Staff Key`=TD.`Timesheet Staff Key`) ';

$sql_totals="select count(*) as num from $table  $where  ";

//print $sql_totals;
$fields="
`Timesheet Missing Clocking Records`,
(`Timesheet Paid Overtime`+`Timesheet Unpaid Overtime`+`Timesheet Working Time`)  worked_time,
`Timesheet Paid Overtime` paid_overtime,
`Timesheet Unpaid Overtime`unpaid_overtime,
`Timesheet Working Time` work_time,
`Timesheet Breaks Time` breaks,
`Timesheet Clocked Time` clocked_time,
 
 
 
`Timesheet Clocking Records`,`Timesheet Ignored Clocking Records`,`Timesheet Key`,`Timesheet Clocked Time`,`Staff Alias`,`Timesheet Staff Key`,`Staff Name`,`Timesheet Date`,`Staff ID`
";

?>
