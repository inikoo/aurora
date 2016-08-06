<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2016 at 13:12:12 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_showcase($data,$smarty,$user,$db) {



	$material=$data['_object'];
	
	
	if (!$material->id) {
		return "";
	}


	
	$smarty->assign('material', $material);

	return $smarty->fetch('showcase/material.tpl');



}


?>
