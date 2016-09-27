<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2016 at 12:56:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by='';




$where=sprintf("where C.`Category Parent Key`=%d  ", $parameters['parent_key']);




$filter_msg='';

if ($parameters['f_field']=='code' and $f_value!='')
	$wheref=" and  `Category Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='label' and $f_value!='')
	$wheref=sprintf(' and `Category Label` REGEXP "[[:<:]]%s" ', addslashes($f_value));
else
	$wheref='';



switch ($parameters['elements_type']) {

case 'status':
	$_elements='';
	$count_elements=0;
	foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
		if ($_value['selected']) {
			$count_elements++;
			
			if($_key=='InProcess')$_key='In Process';
			
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<4) {
		$where.=' and `Product Category Status` in ('.$_elements.')' ;
	}


	break;




}



$db_period=get_interval_db_name($parameters['f_period']);
if (in_array($db_period, array('Total', '3 Year'))) {
	$yb_fields=" '' as sales_1yb";

}else {
	$yb_fields="`Product Category $db_period Acc 1YB Invoiced Amount` as sales_1yb";
}


$_dir=$order_direction;
$_order=$order;


if ($order=='code')
	$order='`Category Code`';
elseif ($order=='label')
	$order='`Category Label`';
elseif ($order=='products')
	$order='products';
elseif ($order=='status')
	$order='`Product Category Status`';
elseif ($order=='active')
	$order='`Product Category Active Products`';
elseif ($order=='in_process')
	$order='`Product Category In Process Products`';
elseif ($order=='suspended')
	$order='`Product Category Suspended Products`';
elseif ($order=='discontinued')
	$order='`Product Category Discontinued Products`';
elseif ($order=='discontinuing')
	$order='`Product Category Discontinuing Products`';
elseif ($order=='delta_sales_year0') {$order="(-1*(`Product Category Year To Day Acc Invoiced Amount`-`Product Category Year To Day Acc 1YB Invoiced Amount`)/`Product Category Year To Day Acc 1YB Invoiced Amount`)";}
elseif ($order=='delta_sales_year1') {$order="(-1*(`Product Category 2 Year Ago Invoiced Amount`-`Product Category 1 Year Ago Invoiced Amount`)/`Product Category 2 Year Ago Invoiced Amount`)";}
elseif ($order=='delta_sales_year2') {$order="(-1*(`Product Category 3 Year Ago Invoiced Amount`-`Product Category 2 Year Ago Invoiced Amount`)/`Product Category 3 Year Ago Invoiced Amount`)";}
elseif ($order=='delta_sales_year3') {$order="(-1*(`Product Category 4 Year Ago Invoiced Amount`-`Product Category 3 Year Ago Invoiced Amount`)/`Product Category 4 Year Ago Invoiced Amount`)";}
elseif ($order=='sales_year1') {$order="`Product Category 1 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year2') {$order="`Product Category 2 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year3') {$order="`Product Category 3 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year4') {$order="`Product Category 4 Year Ago Invoiced Amount`";}
elseif ($order=='sales_year0') {$order="`Product Category Year To Day Acc Invoiced Amount`";}


else
	$order='`Category Code`';


$fields="P.`Product Category Key`,C.`Category Code`,`Category Label`,C.`Category Key`,`Category Store Key`,(`Product Category Active Products`+`Product Category Discontinuing Products`) as products,`Product Category Active Products`,`Product Category Status`,
`Product Category Active Products`,`Product Category In Process Products`,`Product Category Suspended Products`,`Product Category Discontinued Products`,`Product Category Discontinuing Products`,
`Product Category $db_period Acc Invoiced Amount` as sales,`Product Category Year To Day Acc Invoiced Amount`,`Product Category Year To Day Acc 1YB Invoiced Amount`,`Product Category 1 Year Ago Invoiced Amount`,`Product Category 2 Year Ago Invoiced Amount`,`Product Category 3 Year Ago Invoiced Amount`,`Product Category 4 Year Ago Invoiced Amount`,
`Product Category Currency Code`,$yb_fields


 ";
$table=' `Category Dimension` C   left join `Product Category Dimension` P on (P.`Product Category Key`=C.`Category Key`) left join `Product Category Data` D on (D.`Product Category Key`=C.`Category Key`) left join `Product Category DC Data` DC on (DC.`Product Category Key`=C.`Category Key`)';

$sql_totals="select count(distinct C.`Category Key`) as num from $table $where";


?>
