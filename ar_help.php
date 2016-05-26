<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 15:38:13 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


require_once 'common.php';
require_once 'utils/ar_common.php';


$tipo=$_REQUEST['tipo'];


switch ($tipo) {

case 'help':
	$data=prepare_values($_REQUEST, array(

			'state'=>array('type'=>'json array'),
		));

	get_help($data, $modules, $db, $account, $user, $smarty);
	break;
case 'show_help':
	$data=prepare_values($_REQUEST, array(

			'value'=>array('type'=>'string'),
		));
	$_SESSION['show_help']=$data['value'];
	break;
default:
	$response=array('state'=>404, 'resp'=>'Operation not found 2');
	echo json_encode($response);

}


function get_help($data, $modules, $db, $account, $user, $smarty) {

	//print_r($data['state']);


	$title=get_title($data['state'], $account, $user);
	$content=get_content($data['state'], $smarty, $account, $user);

	$response=array(
		'title'=>$title,
		'content'=>$content
	);

	echo json_encode($response);




}


function get_title($state, $account, $user) {

	if ($state['tab']=='supplier.supplier_parts') {
		return _("Supplier's part list & adding supplier parts");
	}elseif ($state['tab']=='employees') {
		if ($user->can_create('staff')) {
			return _('Employees list & adding employees');
		}else {

			return _('Employees list');
		}
	}elseif ($state['tab']=='employee.new') {
		return _('Adding an employee');

	}
	return '';
}


function get_content($state, $smarty, $account, $user) {

	$smarty->assign('user', $user);
	$smarty->assign('object', $state['object']);
	$smarty->assign('key', $state['key']);

   	$smarty->assign('account', $account);



	$template='help/'.$state['module'].'.'.$state['tab'].'.quick.tpl';
	if ($smarty->templateExists($template)) {
		return $smarty->fetch($template);
	}
	return _('There is not help for this section').' '.$state['module'].'.'.$state['tab'];
}


?>
