<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2016 at 11:41:17 CEST, Malaga, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$table='`Upload Dimension` U left join `User Dimension` as UD on (`Upload User Key`=`User Key`)';
$where=' where true';

$wheref='';


$_order=$order;
$_dir=$order_direction;

if ($order=='date')
	$order='`Upload Date`';
elseif ($order=='state')
	$order='`Upload State`';
elseif ($order=='ok')
	$order='`Upload ok`';
elseif ($order=='records')
	$order='`Upload Records`';	
elseif ($order=='errors')
	$order='`Upload Errors`';
elseif ($order=='warnings')
	$order='`Upload Warnings`';			
elseif ($order=='user')
	$order='`User Alias`';				
else
	$order='`Upload Key`';


$sql_totals="select count(Distinct U.`Upload Key`) as num from $table $where  ";

$fields="`Upload Key`,`Upload State`,`Upload Object`,`Upload Records`,`Upload OK`,`Upload Errors`,`Upload Warnings`,`Upload Date`,`User Alias`";

?>
