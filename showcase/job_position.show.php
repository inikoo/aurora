<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 February 2017 at 15:24:16 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_showcase($data, $smarty, $user, $db) {


	if (!$data['_object']->id) {
		return "";
	}


	$smarty->assign('position', $data['_object']);
	return $smarty->fetch('showcase/job_position.tpl');



}


?>