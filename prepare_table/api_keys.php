<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 November 2015 at 17:31:59 CET, Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'user':
        $where=sprintf(" where  U.`User Key`=%d ",$parameters['parent_key']);
        break;
    default:
        $where=" where  true";
        break;
}






$wheref='';
if ($parameters['f_field']=='handle' and $f_value!=''  ) {
	$wheref.=" and  `User Handle` like '".addslashes($f_value)."%'    ";
}



$_order=$order;
$_dir=$order_direction;


if ($order=='handle')
	$order='`User Handle`';
elseif ($order=='valid_ip')
	$order='`Valid IP`';
elseif ($order=='valid_until')
	$order='`SValid Until`';
elseif ($order=='public_key')
	$order='`API Public Key`';
else
	$order='`User API Key`';




$table=' `User API Dimension` UA left join `User Dimension` U on (U.`User Key`=UA.`User Key`) ';

$sql_totals="select count(Distinct `User API Key`) as num from $table  $where  ";


$fields="`User Handle`,UA.`User Key`,`User API Key`,`Valid Until`,`Valid IP`,`API Public Key`";

?>
