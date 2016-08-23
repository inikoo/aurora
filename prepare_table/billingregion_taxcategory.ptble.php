<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 December 2015 at 07:45:06 GMT+8, Macau
 Copyright (c) 2015, Inikoo

 Version 3

*/


if(is_array($parameters['excluded_stores']) and count($parameters['excluded_stores'])>0)
$where=sprintf(' where `Invoice Store Key` not in (%s)  ',join($parameters['excluded_stores'],','));
else
$where=' where true';

if (isset($parameters['period'])) {


    include_once 'utils/date_functions.php';


	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db,$parameters['period'], $parameters['from'], $parameters['to']);



	$where_interval=prepare_mysql_dates($from, $to, '`Invoice Date`');
	
	
	
	$where.=$where_interval['mysql'];
	

}






$wheref='';
if ($parameters['f_field']=='alias' and $f_value!=''  ) {
	$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref=sprintf('  and  `Staff Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}



$_order=$order;
$_dir=$order_direction;


if ($order=='billing_region')
	$order='`Invoice Billing Region`';
if ($order=='tax_code')
	$order='`Invoice Tax Code`';
else if ($order=='invoices')
	$order='invoices';
else if ($order=='refunds')
	$order='refunds';
else if ($order=='customers')
	$order='customers';
else if ($order=='net')
	$order='net';
else if ($order=='tax')
	$order='tax';		
else if ($order=='total')
	$order='total';							
else
	$order='`Invoice Billing Region`';


$group_by='group by `Invoice Billing Region`,`Invoice Tax Code`';

$table='  `Invoice Dimension` as I  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`) ';

$sql_totals="";


$fields="
`Invoice Billing Region`,`Invoice Tax Code`,`Tax Category Name`,
sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
 sum(if(`Invoice Type`!='Invoice',1,0)) as refunds,
  count(distinct `Invoice Customer Key`) as customers,
  sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total ,
  sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net,
  sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax

 
 
";

?>
