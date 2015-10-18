<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 11 October 2015 at 11:22:55 CEST. Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'user':
        $where=sprintf(" where  U.`User Key`=%d ",$parameters['parent_key']);
        break;
    default:
        $where=" where  `User Type`='Staff' ";
        break;
}






$wheref='';
if ($parameters['f_field']=='handle' and $f_value!=''  ) {
	$wheref.=" and  `User Handle` like '%".addslashes($f_value)."%'    ";
}else if ($parameters['f_field']=='ip'   and is_numeric($f_value) ) {
	$wheref.=" and  `IP` like '%".addslashes($f_value)."%'    ";
}



$_order=$order;
$_dir=$order_direction;


if ($order=='handle')
	$order='`User Handle`';
elseif ($order=='ip')
	$order='`IP`';
elseif ($order=='login_date')
	$order='`Start Date`';
elseif ($order=='logout_date')
	$order='`Logout Date`';
else
	$order='`User Log Key`';




$table=' `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`) ';

$sql_totals="select count(Distinct `User Log Key`) as num from $table  $where  ";


$fields="`User Alias`,`User Parent Key`,U.`User Key`,`User Log Key`,`User Handle`,`IP`,`Start Date`,`Logout Date`";

//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
 //   print $sql;
?>
