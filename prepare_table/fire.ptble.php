<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2015 at 12:44:11 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where=sprintf(" where  date(`Timesheet Date`)=%s ", prepare_mysql(date('Y-m-d')));


$group_by=' ';





$wheref='';
if ($parameters['f_field']=='alias' and $f_value!=''  ) {
	$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref=sprintf('  and  `Staff Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}



$_order=$order;
$_dir=$order_direction;


if ($order=='alias') {
	$order="`Staff Alias`";

	
}elseif ($order=='name') {
	$order="`Staff Name`  ";



}elseif ($order=='payroll_id')
	$order='`Staff ID`';


elseif ($order=='staff_formatted_id')
	$order='`Timesheet Staff Key`';
elseif ($order=='clocking_records')
	$order='`clocking_records`';
elseif ($order=='status' ) {
	$order="status_key $order_direction, `Staff Name`";
	$order_direction='';
}else
	$order='`Timesheet Key`';



$table='  `Timesheet Dimension` as TD left join `Staff Dimension` SD on (SD.`Staff Key`=TD.`Timesheet Staff Key`) ';

$sql_totals="select count(distinct `Timesheet Staff Key`) as num from $table  $where  ";

//print $sql_totals;
$fields="

(
    CASE
        WHEN `Timesheet Clocking Records` = 0 THEN 'Off'
        WHEN (`Timesheet Clocking Records` % 2) = 0 THEN 'Out'

        ELSE 'In'
    END) AS status,
(
    CASE
        WHEN `Timesheet Clocking Records` = 0 THEN 2
        WHEN (`Timesheet Clocking Records` % 2) = 0 THEN 1

        ELSE 0
    END) AS status_key,

`Timesheet Clocking Records` clocking_records,

`Timesheet Ignored Clocking Records` clocking_ignored_records,
`Staff Alias`,
`Timesheet Staff Key`,
`Staff Name`,
TD.`Timesheet Key`,
`Staff ID`
";

?>
