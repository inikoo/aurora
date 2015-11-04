<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 13:37:44 CET, Tessera, Ialy
 Copyright (c) 2015, Inikoo

 Version 3

*/


$filter_msg='';

$where="where true";

switch ($parameters['parent']) {
    case 'payment_service_provider':
      $where=sprintf("where `Payment Service Provider Key`=%d",$parameters['parent_key']);
        break;
    default:
      $where="where false";
  
        break;
}

$group='';



$wheref='';
if ( $parameters['f_field']=='name' and $f_value!='' )
	$wheref=sprintf('  and  PA.`Payment Account Name` REGEXP "[[:<:]]%s" ',addslashes($f_value));
elseif ( $parameters['f_field']=='code'  and $f_value!='' )
	$wheref.=" and  PA.`Payment Account Code` like '".addslashes( $f_value )."%'";



$_order=$order;
$_dir=$order_direction;


if ($order=='code')
	$order='PA.`Payment Account Code`';
elseif ($order=='name')
	$order='PA.`Payment Account Name`';
elseif ($order=='transactions')
	$order='PA.`Payment Account Transactions`';

elseif ($order=='payments')
	$order='PA.`Payment Account Payments Amount`';
elseif ($order=='refunds')
	$order='PA.`Payment Account Refunds Amount`';
elseif ($order=='balance')
	$order='PA.`Payment Account Balance Amount`';

else
	$order='PA.`Payment Account Key`';


$table='`Payment Account Dimension` PA ';

$sql_totals="select count(PA.`Payment Account Key`) as num from $table  $where  ";
$fields="`Payment Account Key`,`Payment Account Currency`,`Payment Account Code`,`Payment Account Name`,`Payment Account Transactions`,`Payment Account Payments Amount`,`Payment Account Refunds Amount`,`Payment Account Balance Amount`";


?>
