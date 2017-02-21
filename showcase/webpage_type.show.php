<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 February 2017 at 18:06:05 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

function get_showcase($data, $smarty, $user, $db) {


	if (!$data['_object']->id) {
		return "";
	}


	$smarty->assign('webpage_type', $data['_object']);
	return $smarty->fetch('showcase/webpage_type.tpl');



}


?>