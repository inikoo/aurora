<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 20:26:00 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/





if (count($user->websites)==0) {
	$where='where false ';
}else {
	$where='where true ';
}

switch ($parameters['parent']) {
case('store'):
	$where.=sprintf(' and `Site Store Key`=%d and `Site Key` in (%s)', $parameters['parent_key'], join(',', $user->websites));


	break;
default:
	$where.=sprintf(' and `Site Key` in (%s)', join(',', $user->websites));


	break;

}

$group='';



$wheref='';
if ($parameters['f_field']=='name'  and $f_value!='')
	$wheref.=" and `Site Name` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='url' and $f_value!='')
	$wheref.=" and  `Site URL` like '%".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;



if ($order=='name') {
	$order='`Site Name`';
}elseif ($order=='url') {
	$order='`Site URL`';
}elseif ($order=='users') {
	$order='`Site Total Acc Users`';
}elseif ($order=='code') {
	$order='`Site Code`';

}elseif ($order=='pages') {
	$order='`Site Number Pages`';
}elseif ($order=='products') {
	$order='`Site Number Products`';
}elseif ($order=='visitors') {
	$order='`Site Total Acc Visitors`';
}elseif ($order=='requests') {
	$order='`Site Total Acc Requests`';
}elseif ($order=='sessions') {
	$order='`Site Total Acc Sessions`';
}elseif ($order=='pages_products') {
	$order='`Site Number Pages with Products`';
}elseif ($order=='pages_out_of_stock') {
	$order='`Site Number Pages with Out of Stock Products`';
}elseif ($order=='pages_out_of_stock_percentage') {
	$order='`Site Number Pages with Out of Stock Products`/`Site Number Pages with Products`';
}elseif ($order=='email_reminders_customers') {
	$order='`Site Number Back in Stock Reminder Customers`';
}elseif ($order=='email_reminders_products') {
	$order='`Site Number Back in Stock Reminder Products`';
}elseif ($order=='email_reminders_waiting') {
	$order='`Site Number Back in Stock Reminder Waiting`';
}elseif ($order=='email_reminders_ready') {
	$order='`Site Number Back in Stock Reminder Ready`';
}elseif ($order=='email_reminders_sent') {
	$order='`Site Number Back in Stock Reminder Sent`';
}elseif ($order=='email_reminders_cancelled') {
	$order='`Site Number Back in Stock Reminder Cancelled`';
}elseif ($order=='out_of_stock') {
	$order='`Site Number Out of Stock Products`';
}elseif ($order=='out_of_stock_percentage') {
	$order='`Site Number Out of Stock Products`/`Site Number Products`';
}
else {

	$order='`Site Key`';

}



$table='`Site Dimension`';

$sql_totals="select count(Distinct `Site Key`) as num from $table  $where  ";

$fields="`Site SSL`,
	`Site Number Back in Stock Reminder Customers`,`Site Number Back in Stock Reminder Products`,`Site Number Back in Stock Reminder Waiting`,`Site Number Back in Stock Reminder Ready`,`Site Number Back in Stock Reminder Sent`,`Site Number Back in Stock Reminder Cancelled`,`Site Number Products`,`Site Number Out of Stock Products`,`Site Number Pages with Out of Stock Products`,`Site Number Pages with Products`,`Site Number Pages`,`Site Total Acc Requests`,`Site Total Acc Sessions`,`Site Total Acc Visitors`,`Site Total Acc Users`,`Site Code`,`Site Name`,`Site Key`,`Site URL`
";
?>
