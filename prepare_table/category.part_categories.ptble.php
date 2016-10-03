<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 18:40:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by='';

switch ($parameters['parent']) {



case 'category':
	$where=sprintf("where `Category Parent Key`=%d  ", $parameters['parent_key']);
	break;
default:
	exit('error: unknown parent category: '.$parameters['parent']);
}



if (isset($parameters['f_period'])) {

	$db_period=get_interval_db_name($parameters['f_period']);
	if (in_array($db_period, array('Total', '3 Year'))) {
		$yb_fields=" '' as dispatched_1yb,'' as sales_1yb,";

	}else {
		$yb_fields="`Part Category $db_period Acc 1YB Dispatched` as dispatched_1yb,`Part Category $db_period Acc 1YB Invoiced Amount` as sales_1yb,";
	}

}else {
	$db_period='Total';
	$yb_fields=" '' as dispatched_1yb,'' as sales_1yb,";
}


$filter_msg='';

if ($parameters['f_field']=='code' and $f_value!='')
	$wheref=" and  `Category Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='label' and $f_value!='')
	$wheref=sprintf(' and `Category Label` REGEXP "[[:<:]]%s" ', addslashes($f_value));
else
	$wheref='';


$_dir=$order_direction;
$_order=$order;


if ($order=='code')
	$order='`Category Code`';
elseif ($order=='label')
	$order='`Category Label`';
elseif ($order=='subjects')
	$order='`Category Number Subjects`';
elseif ($order=='subjects_active')
	$order='`Category Number Active Subjects`';
elseif ($order=='subjects_no_active')
	$order='`Category Number No Active Subjects`';
elseif ($order=='subcategories')
	$order='`Category Children`';
elseif ($order=='percentage_assigned')
	$order='`Category Number Subjects`/(`Category Number Subjects`+`Category Subjects Not Assigned`)';
elseif ($order=='low') {
	$order="`Part Category Number Low Parts`";
}elseif ($order=='surplus') {
	$order="`Part Category Number Surplus Parts`";
}elseif ($order=='optimal') {
	$order="`Part Category Number Optimal Parts`";
}elseif ($order=='low') {
	$order="`Part Category Number Low Parts`";
}elseif ($order=='critical') {
	$order="`Part Category Number Critical Parts`";
}elseif ($order=='out_of_stock') {
	$order="`Supplier Number Out Of Stock Parts`";
}elseif ($order=='stock_error') {
	$order="`Supplier Number Error Parts`";
}elseif ($order=='sales') {
	$order="`Part Category $db_period Acc Invoiced Amount`";
}
elseif ($order=='delta_sales_year0') {$order="(-1*(`Part Category Year To Day Acc Invoiced Amount`-`Part Category Year To Day Acc 1YB Invoiced Amount`)/`Part Category Year To Day Acc 1YB Invoiced Amount`)";}
elseif ($order=='delta_sales_year1') {$order="(-1*(`Part Category 2 Year Ago Invoiced Amount`-`Part Category 1 Year Ago Invoiced Amount`)/`Part Category 2 Year Ago Invoiced Amount`)";}
elseif ($order=='delta_sales_year2') {$order="(-1*(`Part Category 3 Year Ago Invoiced Amount`-`Part Category 2 Year Ago Invoiced Amount`)/`Part Category 3 Year Ago Invoiced Amount`)";}
elseif ($order=='delta_sales_year3') {$order="(-1*(`Part Category 4 Year Ago Invoiced Amount`-`Part Category 3 Year Ago Invoiced Amount`)/`Part Category 4 Year Ago Invoiced Amount`)";}
elseif ($order=='sales_year1') {$order="`Part Category 1 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year2') {$order="`Part Category 2 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year3') {$order="`Part Category 3 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year4') {$order="`Part Category 4 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year0') {$order="`Part Category Year To Day Acc Invoiced Amount`";}
elseif ($order=='sales_total') {$order="`Part Category Total Acc Invoiced Amount`";}
elseif ($order=='dispatched_total') {$order="`Part Category Total Acc Dispatched`";}
elseif ($order=='customer_total') {$order="`Part Category Total Acc Customers`";}
elseif ($order=='percentage_no_stock') {$order="percentage_no_stock";}

else
	$order='`Category Key`';


$fields="
`Part Category $db_period Acc Dispatched` as dispatched,
`Part Category $db_period Acc Invoiced Amount` as sales,
$yb_fields `Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,
`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,

`Part Category Year To Day Acc Invoiced Amount`,`Part Category Year To Day Acc 1YB Invoiced Amount`,
`Part Category 1 Year Ago Invoiced Amount`,`Part Category 2 Year Ago Invoiced Amount`,`Part Category 3 Year Ago Invoiced Amount`,`Part Category 4 Year Ago Invoiced Amount`,`Part Category 5 Year Ago Invoiced Amount`,
`Part Category Quarter To Day Acc Invoiced Amount`,`Part Category Quarter To Day Acc 1YB Invoiced Amount`,
`Part Category 1 Quarter Ago Invoiced Amount`,`Part Category 2 Quarter Ago Invoiced Amount`,`Part Category 3 Quarter Ago Invoiced Amount`,`Part Category 4 Quarter Ago Invoiced Amount`,
`Part Category 1 Quarter Ago 1YB Invoiced Amount`,`Part Category 2 Quarter Ago 1YB Invoiced Amount`,`Part Category 3 Quarter Ago 1YB Invoiced Amount`,`Part Category 4 Quarter Ago 1YB Invoiced Amount`,

`Part Category Year To Day Acc Dispatched`,`Part Category Year To Day Acc 1YB Dispatched`,
`Part Category 1 Year Ago Dispatched`,`Part Category 2 Year Ago Dispatched`,`Part Category 3 Year Ago Dispatched`,`Part Category 4 Year Ago Dispatched`,`Part Category 5 Year Ago Dispatched`,
`Part Category Quarter To Day Acc Dispatched`,`Part Category Quarter To Day Acc 1YB Dispatched`,
`Part Category 1 Quarter Ago Dispatched`,`Part Category 2 Quarter Ago Dispatched`,`Part Category 3 Quarter Ago Dispatched`,`Part Category 4 Quarter Ago Dispatched`,
`Part Category 1 Quarter Ago 1YB Dispatched`,`Part Category 2 Quarter Ago 1YB Dispatched`,`Part Category 3 Quarter Ago 1YB Dispatched`,`Part Category 4 Quarter Ago 1YB Dispatched`,
`Part Category Total Acc Invoiced Amount`,`Part Category Total Acc Dispatched`,`Part Category Total Acc Customers`,
(`Part Category Number Out Of Stock Parts`+`Part Category Number Error Parts`)/(`Part Category Number Surplus Parts`+`Part Category Number Optimal Parts`+`Part Category Number Low Parts`+`Part Category Number Critical Parts`+`Part Category Number Out Of Stock Parts`+`Part Category Number Error Parts`) as percentage_no_stock,

`Part Category Number Surplus Parts`,`Part Category Number Optimal Parts`,`Part Category Number Low Parts`,`Part Category Number Critical Parts`,`Part Category Number Out Of Stock Parts`,`Part Category Number Error Parts`


 ";
$table='`Category Dimension` C left join `Part Category Dimension` D on (D.`Part Category Key`=C.`Category Key`)  left join `Part Category Data` PDC on (PDC.`Part Category Key`=C.`Category Key`) ';

$sql_totals="select count(distinct `Category Key`) as num from $table $where";


?>
