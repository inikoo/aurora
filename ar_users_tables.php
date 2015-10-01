<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 11:45:16 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('customers')) {
	echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'staff':
	staff(get_table_parameters(), $db, $user);
	break;
case 'groups':
	groups(get_table_parameters(), $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function staff($_data, $db, $user) {
	global $db;
	$rtext_label='user';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

//	print $sql;


	foreach ($db->query($sql) as $data) {
		if ($data['User Active']=='Yes')
			$is_active=_('Yes');
		else
			$is_active=_('No');

		$groups=preg_split('/,/', $data['Groups']);
		$stores=preg_split('/,/', $data['Stores']);
		
		
		
		
		$warehouses=preg_split('/,/', $data['Warehouses']);
		$sites=preg_split('/,/', $data['Sites']);

		$adata[]=array(
			'id'=>(integer) $data['User Key'],
			'handle'=>$data['User Handle'],
			'name'=>$data['User Alias'],
			'active'=>$is_active,
			'logins'=>number($data['User Login Count']),
			'last_login'=>($data ['User Last Login']==''?'':strftime( "%e %b %Y %H:%M %Z", strtotime( $data ['User Last Login']." +00:00" ) )),
			'fail_logins'=>number($data['User Failed Login Count']),
			'fail_last_login'=>($data ['User Last Failed Login']==''?'':strftime( "%e %b %Y %H:%M %Z", strtotime( $data ['User Last Failed Login']." +00:00" ) )),

			'groups'=>$data['Groups'],
			'stores'=>$stores,
			'warehouses'=>$warehouses,
			'websites'=>$data['Sites'],
		);

	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}




?>
