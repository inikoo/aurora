<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 14:30:52 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_warehouse_showcase($data, $smarty, $user, $db) {


	$warehouse=new Warehouse($data['key']);

	$smarty->assign('warehouse', $warehouse);

	return $smarty->fetch('showcase/warehouse.tpl');



}

function get_locked_warehouse_showcase($data, $smarty, $user, $db){


	$smarty->assign('warehouse', $data['_object']);

	return $smarty->fetch('showcase/warehouse.locked.tpl');

}

?>
