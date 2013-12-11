<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$list_key=$_REQUEST['id'];

} else {
	exit("wrong key");
}

$sql=sprintf("select `List Scope` from `List Dimension` where `List Key`=%d",$list_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	//'Customer','Order','Invoice','Delivery Note','Product','Part'
	switch ($row['List Scope']) {
	case 'Customer':
		header('Location: customers_list.php?id='.$list_key);
		exit();
		break;
	case 'Part':
		header('Location: parts_list.php?id='.$list_key);
		exit();
		break;
	}

}else {
	exit("list_not_found");
}

?>
