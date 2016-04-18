<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 April 2016 at 19:00:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'set_table_view':

	$data=prepare_values($_REQUEST, array(
			'tab'=>array('type'=>'string'),
			'table_view'=>array('type'=>'string'),


		));

	set_table_view($data);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function set_table_view($data) {

	if (isset($_SESSION['table_state'][$data['tab']])) {
		$_SESSION['table_state'][$data['tab']]['view']=$data['table_view'];
		print 'ok';
	}


}




?>
