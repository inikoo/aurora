<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2015 at 13:13:42 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_account_setup_navigation($data, $smarty, $user, $db, $account) {


	$sections=array();
	//$sections['account']['selected']=true;
	$skip=false;
	if ($data['section']=='setup_add_employees') {
		$title=_('Add employees');
		$skip=true;
	}elseif ($data['section']=='setup_root_user') {
		$title=_('Set up root user');
	}elseif ($data['section']=='setup_add_warehouse') {
		$title=_('Add warehouse');
	}elseif ($data['section']=='setup_add_store') {
		$title=_('Add store');
	}else {
		$title=_('Account set up');
	}



	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account')),
		'skip'=>$skip

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_account_navigation($data, $smarty, $user, $db, $account) {


	$sections=get_sections('account', '');
	$sections['account']['selected']=true;
	$title=_('Account').' <span class="id">'.$data['_object']->get('Code').'</span>';

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_new_api_key_navigation($data, $smarty, $user, $db, $account) {


	$sections=get_sections('account', '');
	$sections['account']['selected']=true;

	$title=_('New API key');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Staff user"), 'reference'=>'account/user/'.$data['parent_key']);
	$left_buttons=array();
	$left_buttons[]=$up_button;
	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_orders_index_navigation($data, $smarty, $user, $db, $account) {



	$sections=array();
	$sections['settings']['selected']=true;
	$title=_("Order's index");

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search orders all stores'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_settings_navigation($data, $smarty, $user, $db, $account) {



	$sections=get_sections('account', '');
	$sections['settings']['selected']=true;
	$title=_('Settings');

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_data_sets_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Data sets');

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_timeseries_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Time series');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_timeserie_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Time series').' <span class="id">'.$data['_object']->get('Name').'</span>';
	$object=$data['_object'];

	$left_buttons=array();
	$right_buttons=array();

	switch ($data['parent']) {
	case 'account':
		$tab='timeseries';


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($result2=$db->query($sql)) {
			if ($row2= $result2->fetch()  and $row2['num']>1) {





				$sql=sprintf("select `Timeseries Key` object_name,TS.`Timeseries Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND TS.`Timeseries Key` < %d))  order by $_order_field desc , TS.`Timeseries Key` desc limit 1",
					"$table $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row= $result->fetch()  and $row2['num']>1) {
						$prev_key=$row['object_key'];
						$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				$sql=sprintf("select `Timeseries Key` object_name,TS.`Timeseries Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND TS.`Timeseries Key` > %d))  order by $_order_field   , TS.`Timeseries Key`  limit 1",
					"$table  $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row= $result->fetch()  and $row2['num']>1) {
						$next_key=$row['object_key'];
						$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
				}

				$up_button=array('icon'=>'arrow-up', 'title'=>_("Time series"), 'reference'=>'account/data_sets/timeseries');

				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'timeseries/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'timeseries/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}


			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}




		break;

	}




	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_material_navigation($data, $smarty, $user, $db, $account) {


	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title='<i class="fa fa-puzzle-piece" aria-hidden="true"></i> <span class="id Material_Name">'.$data['_object']->get('Name').'</span>';
	$object=$data['_object'];

	$left_buttons=array();
	$right_buttons=array();

	switch ($data['parent']) {
	case 'account':
		$tab='data_sets.materials';


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($result2=$db->query($sql)) {
			if ($row2= $result2->fetch()  and $row2['num']>1) {





				$sql=sprintf("select `Material Key` object_name,M.`Material Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND M.`Material Key` < %d))  order by $_order_field desc , M.`Material Key` desc limit 1",
					"$table $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row= $result->fetch()  and $row2['num']>1) {
						$prev_key=$row['object_key'];
						$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				$sql=sprintf("select `Material Key` object_name,M.`Material Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND M.`Material Key` > %d))  order by $_order_field   , M.`Material Key`  limit 1",
					"$table  $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row= $result->fetch()  and $row2['num']>1) {
						$next_key=$row['object_key'];
						$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
				}

				$up_button=array('icon'=>'arrow-up', 'title'=>_("Time series"), 'reference'=>'account/data_sets/materials');

				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'account/data_sets/materials/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'account/data_sets/materials/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}


			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}




		break;

	}




	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_images_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Images');
	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_attachments_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Attachments');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_uploads_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Records uploads');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_materials_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Materials');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_upload_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Upload').sprintf(' %04d', $data['_object']->id);

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_isf_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Inventory timeseries');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_osf_navigation($data, $smarty, $user, $db, $account) {

	$sections=get_sections('account', '');
	$sections['data_sets']['selected']=true;
	$title=_('Transactions timeseries');

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Data sets"), 'reference'=>'account/data_sets');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


function get_users_navigation($data, $smarty, $user, $db, $account) {



	$sections=get_sections('account', '');
	$sections['users']['selected']=true;
	$title=_('Users');

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}






function get_staff_navigation($data, $smarty, $user, $db, $account) {


	$block_view=$data['section'];

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Users"), 'reference'=>'account/users');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$right_buttons=array();
	$sections=get_sections('account', '');

	$sections['users']['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Employees users'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_contractors_navigation($data, $smarty, $user, $db, $account) {


	$block_view=$data['section'];


	$up_button=array('icon'=>'arrow-up', 'title'=>_("Users"), 'reference'=>'account/users');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$right_buttons=array();
	$sections=get_sections('account', '');

	$sections['users']['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Contractor users'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_suppliers_navigation($data, $smarty, $user, $db, $account) {


	$block_view=$data['section'];


	$up_button=array('icon'=>'arrow-up', 'title'=>_("Users"), 'reference'=>'account/users');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$right_buttons=array();
	$sections=get_sections('account', '');

	$sections['users']['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Suppliers users'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_agents_navigation($data, $smarty, $user, $db, $account) {


	$block_view=$data['section'];


	$up_button=array('icon'=>'arrow-up', 'title'=>_("Users"), 'reference'=>'account/users');
	$left_buttons=array();
	$left_buttons[]=$up_button;

	$right_buttons=array();
	$sections=get_sections('account', '');

	$sections['users']['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Agents users'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



function get_staff_user_navigation($data, $smarty, $user, $db, $account) {



	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='account.users.staff';
			$_section='staff';
			break;
		case 'group':
			$tab='users.staff.groups';
			$_section='staff';
			break;

		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];


			switch ($data['_object']->get('User Type')) {
			case 'Staff':
				$parameters['tab']='account.users.staff';
				break;
			case 'Contractor':
				$parameters['tab']='account.users.contractors';
				break;
			case 'Agent':
				$parameters['tab']='account.users.agents';
				break;
			case 'Supplier':
				$parameters['tab']='account.users.suppliers';
				break;
			default:

				break;
			}

		}




		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($result2=$db->query($sql)) {
			if ($row2= $result2->fetch()  and $row2['num']>1) {





				$sql=sprintf("select `User Alias` object_name,U.`User Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND U.`User Key` < %d))  order by $_order_field desc , U.`User Key` desc limit 1",
					"$table $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


				$sql=sprintf("select `User Alias` object_name,U.`User Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Key` > %d))  order by $_order_field   , U.`User Key`  limit 1",
					"$table  $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
				}


			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}






		if ($data['parent']=='group') {



			include_once 'class.Category.php';
			$category=new Category($data['parent_key']);


			$category_keys=preg_split('/\>/', preg_replace('/\>$/', '', $category->data['Category Position']));
			array_pop($category_keys);
			if (count($category_keys)>0) {
				$sql=sprintf("select `Category Code`,`Category Key` from `Category Dimension` where `Category Key` in (%s)", join(',', $category_keys));

				if ($result=$this->db->query($sql)) {
					foreach ($result as $row) {
						//TODO
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




			}


			$up_button=array('icon'=>'arrow-up', 'title'=>_("Category").' '.$category->data['Category Code'], 'url'=>'supplier_category.php?id='.$category->id);

		}
		else {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Staff users"), 'reference'=>'account/users/staff');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'account/user/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'account/user/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}






		}
	}
	else {
		$_section='staff';

	}

	$sections=get_sections('account', '');


	$sections['users']['selected']=true;

	switch ($object->get('User Type')) {
	case 'Staff':
		$parent_reference='employee/'.$object->get('User Parent Key');
		$parent_icon='<i style="font-size:80%;padding-left:10px" class="fa fa-hand-rock-o" aria-hidden="true"></i>';
		break;
	case 'Contractor':
		$parent_reference='contractor/'.$object->get('User Parent Key');
		$parent_icon='<i style="font-size:80%;padding-left:10px" class="fa fa-hand-spock-o" aria-hidden="true"></i>';
		break;
	case 'Supplier':
		$parent_reference='supplier/'.$object->get('User Parent Key');
		$parent_icon='<i style="font-size:80%;padding-left:10px" class="fa fa-ship" aria-hidden="true"></i>';
		break;
	case 'Agent':
		$parent_reference='agent/'.$object->get('User Parent Key');
		$parent_icon='<i style="font-size:80%;padding-left:10px" class="fa fa-user-secret" aria-hidden="true"></i>';
		break;
	default:

		break;
	}


	$title= '<span class="id">'.$object->get('User Handle').'</span> <span class="small button" onClick="change_view(\''.$parent_reference.'\')"  > '.$parent_icon.' '.$object->get('User Alias').' </span> ';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_deleted_staff_user_navigation($data, $smarty, $user, $db, $account) {



	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='account.deleted.users';
			$_section='staff';
			break;


		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];



		}




		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($result2=$db->query($sql)) {
			if ($row2= $result2->fetch()  and $row2['num']>1) {





				$sql=sprintf("select `User Deleted Alias` object_name,U.`User Deleted Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND U.`User Deleted Key` < %d))  order by $_order_field desc , U.`User Deleted Key` desc limit 1",
					"$table $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


				$sql=sprintf("select `User Deleted Alias` object_name,U.`User Deleted Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Deleted Key` > %d))  order by $_order_field   , U.`User Deleted Key`  limit 1",
					"$table  $where $wheref",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
				}


			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}








		$up_button=array('icon'=>'arrow-up', 'title'=>_("Staff users"), 'reference'=>'account/users/');

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'account/user/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'account/user/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}







	}
	else {
		$_section='staff';

	}

	$sections=get_sections('account', '');


	$sections['users']['selected']=true;




	$title= '<span class="id">'.$object->get('User Deleted Handle').'</span> ';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_api_key_navigation($data, $smarty, $user, $db, $account) {

	global $smarty, $user;

	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='account.api_keys';
			$_section='account';
			break;
		case 'user':
			$tab='staff.user.api_keys';
			$_section='account';
			break;

		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}





		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");

		if ($result2=$db->query($sql)) {
			if ($row2 = $result2->fetch()  and  $row2['num']>1) {


				$sql=sprintf("select `API Key Key` object_name,AKD.`API Key Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND AKD.`API Key Key` < %d))  order by $_order_field desc , AKD.`API Key Key` desc limit 1",

					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);

				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				$sql=sprintf("select `API Key Key` object_name,AKD.`API Key Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND AKD.`API Key Key` > %d))  order by $_order_field   , AKD.`API Key Key`  limit 1",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);

				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
				}



			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		if ($data['parent']=='user') {

			$system_user=new User($data['parent_key']);


			$up_button=array('icon'=>'arrow-up', 'title'=>_("Staff user").' '.$system_user->get('Alias'), 'reference'=>'account/user/'.$system_user->id);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'account/user/'.$system_user->id.'/api_key/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'account/user/'.$system_user->id.'/api_key/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}

		}
		else {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Staff users"), 'reference'=>'account/users/staff');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'account/uapi_keyser/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'account/api_key/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}






		}
	}
	else {
		$_section='staff';

	}

	$sections=get_sections('account', '');


	$sections['users']['selected']=true;



	$title= '<span class="id">'.$object->get('API Key Code').' ('.$object->get_formatted_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}



function get_profile_navigation($data, $smarty, $user, $db, $account) {




	$title=_('My profile');

	$_content=array(
		'sections_class'=>'',
		'sections'=>array(),
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>_('Search account'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}


?>
