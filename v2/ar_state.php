<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'set_table_view':
	$data=prepare_values($_REQUEST,array(
			'tab'=>array('type'=>'string'),
			'table_view'=>array('type'=>'string'),
		));
	set_table_view($data);
	break;
default:
	$response=array('state'=>405,'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
}

function set_table_view($_data) {

	$_SESSION['table_state'][$_data['tab']]['view']=$_data['table_view'];
	$response=array('state'=>200);
	echo json_encode($response);


}

?>
