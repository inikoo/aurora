<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 12:18:06 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


//print_r($parameters);

switch ($parameters['tab']) {
case 'account.users.staff':
	$where=" where  `User Type`='Staff' ";
	break;
case 'account.users.contractors':
	$where=" where  `User Type`='Contractor' ";
	break;	
case 'account.users.agents':
	$where=" where  `User Type`='Agent' ";
	break;
case 'account.users.suppliers':
	$where=" where  `User Type`='Supplier' ";
	break;
default:
	exit('tab not configured (users.ptable.php)');
	break;
}




$wheref='';
if ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref.=" and  `User Alias` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field']=='handle' and $f_value!=''  ) {
	$wheref.=" and  `User Handle` like '".addslashes($f_value)."%'    ";
}else if ($parameters['f_field']=='position_id' or $parameters['f_field']=='area_id'   and is_numeric($f_value) ) {
	$wheref.=sprintf(" and  %s=%d ", $parameters['f_field'], $f_value);
}




$_order=$order;
$_dir=$order_direction;

if ($order=='name')
	$order='`User Alias`';
elseif ($order=='handle')
	$order='`User Handle`';
elseif ($order=='active')
	$order='`User Active`';
elseif ($order=='logins')
	$order='`User Login Count`';
elseif ($order=='last_login')
	$order='`User Last Login`';
elseif ($order=='fail_logins')
	$order='`User Failed Login Count`';
elseif ($order=='fail_last_login')
	$order='`User Last Failed Login`';
elseif ($order=='position')
	$order='position';
else
	$order='`User Key`';


$table='`User Dimension` U left join `Staff Dimension` SD  on (`User Parent Key`=`Staff Key`)';

$sql_totals="select count(Distinct U.`User Key`) as num from $table  $where  ";

$fields="`User Failed Login Count`,`User Last Failed Login`,`User Last Login`,`User Login Count`,`User Alias`,`User Handle`,
	(select GROUP_CONCAT(S.`Store Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB  left join `Store Dimension` S on (URSB.`Scope Key`=S.`Store Key`) where URSB.`User Key`=U.`User Key` and `Scope`='Store' ) as Stores,
	(select GROUP_CONCAT(S.`Warehouse Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB left join `Warehouse Dimension` S on (URSB.`Scope Key`=S.`Warehouse Key`) where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,
	(select GROUP_CONCAT(S.`Site Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB left join `Site Dimension` S on (URSB.`Scope Key`=S.`Site Key`)  where URSB.`User Key`=U.`User Key`and `Scope`='Website'  ) as Sites ,

	(select GROUP_CONCAT(S.`User Group Name` SEPARATOR ', ') from `User Group User Bridge` URSB left join `User Group Dimension` S on (URSB.`User Group Key`=S.`User Group Key`)   where URSB.`User Key`=U.`User Key` ) as Groups,`User Key`,`User Active`,`Staff Key`
";
?>
