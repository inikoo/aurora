<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$table='`Product Department Dimension` D left join `Product Department Data Dimension` DD on (DD.`Product Department Key`=D.`Product Department Key`)';


if (count($user->stores)==0)
	$where="where false";
else {

	switch ($parent) {
	case('store'):
		if (in_array($parent_key,$user->stores))
			$where=sprintf("where  `Product Department Store Key`=%d",$parent_key);
		else
			$where=sprintf("where  false");
		break;
	default:

		$where=sprintf("where `Product Department Store Key` in (%s)",join(',',$user->stores));

	}
}

$filter_msg='';
$wheref=wheref_departments($f_field,$f_value);



?>
