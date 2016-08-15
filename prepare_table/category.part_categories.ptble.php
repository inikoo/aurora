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

$db_period=get_interval_db_name($parameters['f_period']);
if(in_array($db_period,array('Total','3 Year'))){
$yb_fields=" '' as revenue_1y";

}else{
$yb_fields="`Part Category $db_period Acc 1YB Sold Amount` as revenue_1y";
}


$filter_msg='';

if ($parameters['f_field']=='code' and $f_value!='')
	$wheref=" and  `Category Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='label' and $f_value!='')
	$wheref=sprintf(' and `Category Label` REGEXP "[[:<:]]%s" ',addslashes($f_value));
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
}	elseif ($order=='stock_error') {
	$order="`Supplier Number Error Parts`";
}	
elseif ($order=='delta_revenue_year0') {$order="(-1*(`Part Category Year To Day Acc Sold Amount`-`Part Category Year To Day Acc 1YB Sold Amount`)/`Part Category Year To Day Acc 1YB Sold Amount`)";}
elseif ($order=='delta_revenue_year1') {$order="(-1*(`Part Category 2 Year Ago Sold Amount`-`Part Category 1 Year Ago Sold Amount`)/`Part Category 2 Year Ago Sold Amount`)";}
elseif ($order=='delta_revenue_year2') {$order="(-1*(`Part Category 3 Year Ago Sold Amount`-`Part Category 2 Year Ago Sold Amount`)/`Part Category 3 Year Ago Sold Amount`)";}
elseif ($order=='delta_revenue_year3') {$order="(-1*(`Part Category 4 Year Ago Sold Amount`-`Part Category 3 Year Ago Sold Amount`)/`Part Category 4 Year Ago Sold Amount`)";}
elseif ($order=='revenue_year1') {$order="`Part Category 1 Year Ago Sold Amount`";}
elseif ($order=='revenue_year2') {$order="`Part Category 2 Year Ago Sold Amount`";}
elseif ($order=='revenue_year3') {$order="`Part Category 3 Year Ago Sold Amount`";}
elseif ($order=='revenue_year4') {$order="`Part Category 4 Year Ago Sold Amount`";}
elseif ($order=='revenue_year0') {$order="`Part Category Year To Day Acc Sold Amount`";}

else
	$order='`Category Key`';


$fields="
$yb_fields,`Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,
`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,
`Part Category $db_period Acc Sold Amount` as revenue,`Part Category Year To Day Acc Sold Amount`,`Part Category Year To Day Acc 1YB Sold Amount`,`Part Category 1 Year Ago Sold Amount`,`Part Category 2 Year Ago Sold Amount`,`Part Category 3 Year Ago Sold Amount`,`Part Category 4 Year Ago Sold Amount`,
`Part Category Number Surplus Parts`,`Part Category Number Optimal Parts`,`Part Category Number Low Parts`,`Part Category Number Critical Parts`,`Part Category Number Out Of Stock Parts`,`Part Category Number Error Parts`

 ";
$table='`Category Dimension` C left join `Part Category Dimension` D on (D.`Part Category Key`=C.`Category Key`) ';

$sql_totals="select count(distinct `Category Key`) as num from $table $where";


?>
