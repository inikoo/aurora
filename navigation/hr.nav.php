<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 13:55:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_employees_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('hr', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Employees'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_contractors_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('hr', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Contractors'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_organization_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('hr', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Organization'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



function get_employee_navigation($data, $smarty, $user, $db) {



	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='employees';
			$_section='employees';
			break;
		case 'users':
			$tab='users.staff.users';
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
		}

		$extra_where=' and `Staff Currently Working`="Yes"';
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



				$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND SD.`Staff Key` < %d))  order by $_order_field desc , SD.`Staff Key` desc limit 1",

					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);

				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("Employee").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND SD.`Staff Key` > %d))  order by $_order_field   , SD.`Staff Key`  limit 1",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("Employee").' '.$row['object_name'].' ('.$row['object_key'].')';

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



		if ($data['parent']=='account') {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Employees"), 'reference'=>'hr');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'employee/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'employee/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}






		}
	}
	else {
		$_section='staff';

	}

	$sections=get_sections('hr', '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<span class="id Staff_Name">'.$object->get('Staff Name').'</span> <span class="id">('.$object->get_formatted_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_employee_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('hr', '');

	$_section='employees';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Employees"), 'reference'=>'hr');


	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._('New Employee').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_contractor_navigation($data, $smarty, $user, $db) {



	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='contractors';
			$_section='contractors';
			break;
		case 'users':
			$tab='users.staff.users';
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
		}

		$extra_where=' and `Staff Currently Working`="Yes"';
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



				$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND SD.`Staff Key` < %d))  order by $_order_field desc , SD.`Staff Key` desc limit 1",

					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);

				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("Contractor").' '.$row['object_name'].' ('.$row['object_key'].')';
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}



				$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND SD.`Staff Key` > %d))  order by $_order_field   , SD.`Staff Key`  limit 1",
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("Contractor").' '.$row['object_name'].' ('.$row['object_key'].')';

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



		if ($data['parent']=='account') {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Contractors"), 'reference'=>'hr/contractors');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'contractor/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'contractor/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}






		}
	}
	else {
		$_section='hr';

	}

	$sections=get_sections('hr', '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<span class="id Staff_Name">'.$object->get('Staff Name').'</span> <span class="id">('.$object->get_formatted_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_contractor_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('hr', '');

	$_section='contractors';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Contractors"), 'reference'=>'hr/contractors');


	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._('New contractor').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_timesheet_navigation($data, $smarty, $user, $db) {



	if ($data['object']) {
		$object=$data['_object'];
		$object->get_staff_data();
	}

	$left_buttons=array();
	$right_buttons=array();





	switch ($data['parent']) {
	case 'account':
		$tab='employees.timesheets';
		$_section='timesheets';
		break;
	case 'employee':
		$tab='employee.timesheets';
		$_section='timesheets';
		break;
	case 'timesheet':
		$tab='timesheet.timesheets';
		$_section='timesheets';
		break;
	case 'week':
		$tab='timesheet.timesheets';
		$_section='timesheets';
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


			$sql=sprintf("select concat(`Staff Alias`,`Timesheet Date`) object_name,TD.`Timesheet Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND TD.`Timesheet Key` < %d))  order by $_order_field desc , TD.`Timesheet Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);



			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$prev_key=$row['object_key'];
					$prev_title=_("Timesheet").' '.$row['object_name'].' ('.$row['object_key'].')';
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}





			$sql=sprintf("select concat(`Staff Alias`,`Timesheet Date`) object_name,TD.`Timesheet Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND TD.`Timesheet Key` > %d))  order by $_order_field   , TD.`Timesheet Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$next_key=$row['object_key'];
					$next_title=_("Timesheet").' '.$row['object_name'].' ('.$row['object_key'].')';
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



	switch ($data['parent']) {
	case 'account':


		$up_button=array('icon'=>'arrow-up', 'title'=>_("Employees"), 'reference'=>'hr');

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'timesheet/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'timesheet/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}







		break;
	case 'employee':

		include_once 'class.Staff.php';
		$employee=new Staff($data['parent_key']);

		$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_('Employee: %s'), $employee->get('Name')), 'reference'=>'employee/'.$data['parent_key']);

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'employee/'.$data['parent_key'].'/timesheet/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'employee/'.$data['parent_key'].'/timesheet/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}




		break;
	case 'timesheet':

		break;
	case 'week':

		break;


	}






	$sections=get_sections('hr', '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<span class=" Timesheet_Date">'.$object->get('Date').'</span>, <span class=" Timesheet_Staff_Name">'.$object->get('Timesheet Staff Name').'</span>  <span class="id hide">('.$object->get_formatted_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_employee_attachment_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('hr', '');

	$_section='employees';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	include_once 'class.Staff.php';
	$employee=new Staff($data['parent_key']);

	$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_('Employee: %s'), $employee->get('Name')), 'reference'=>'employee/'.$data['parent_key']);


	$left_buttons[]=$up_button;


	$title= '<span>'.sprintf(_('New attachment for %s'), '<span class="id">'.$employee->get('Name').'</span>').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_employee_user_navigation($data, $smarty, $user, $db) {


	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('hr', '');

	$_section='employees';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	include_once 'class.Staff.php';
	$employee=new Staff($data['parent_key']);

	$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_('Employee: %s'), $employee->get('Name')), 'reference'=>'employee/'.$data['parent_key']);


	$left_buttons[]=$up_button;


	$title= '<span >'.sprintf(_('New system user for %s'), '<span class="id">'.$employee->get('Name').'</span>').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_employee_attachment_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('hr', '');

	$_section='employees';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	include_once 'class.Staff.php';
	$employee=new Staff($data['parent_key']);

	$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_('Employee: %s'), $employee->get('Name')), 'reference'=>'employee/'.$data['parent_key']);

	$right_buttons[]=array('icon'=>'download', 'title'=>_('Download'), 'id'=>'download_button' );
	$left_buttons[]=$up_button;

	$title= _('Attachment').' <span class="id Attachment_Caption">'.$data['_object']->get('Caption').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_timesheets_navigation($data, $smarty, $user, $db) {




	$left_buttons=array();
	$right_buttons=array();

	//print_r($data);

	switch ($data['parent']) {

	case 'day':

		$year=substr($data['parent_key'], 0, 4);
		$month=substr($data['parent_key'], 4, 2);
		$day=substr($data['parent_key'], 6, 2);

		$date=strtotime("$year-$month-$day");


		$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_("Employees' calendar %s"), date('Y')   ) , 'reference'=>'timesheets/year/'.$year);

		$title=sprintf(_("Employees' calendar %s"), strftime("%a %e %b %Y", $date)  );


		$date=strtotime("$year-$month-$day -1 day");



		$prev_button=array('icon'=>'arrow-left', 'title'=>sprintf(_("Employees' calendar %s"), strftime("%a %e %b %Y", $date)  ), 'reference'=>'timesheets/day/'.date('Ymd', $date));

		$date=strtotime("$year-$month-$day +1 day");



		$next_button=array('icon'=>'arrow-right', 'title'=>sprintf(_("Employees' calendar %s"), strftime("%a %e %b %Y", $date)  ), 'reference'=>'timesheets/day/'.date('Ymd', $date));

		break;


	case 'month':

		$year=substr($data['parent_key'], 0, 4);
		$month=substr($data['parent_key'], 4, 2);

		$date=strtotime("$year-$month-01");


		$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_("Employees' calendar %s"), date('Y')   ) , 'reference'=>'timesheets/year/'.$year);

		$title=sprintf(_("Employees' calendar %s"), strftime("%b %Y", $date)  );


		$date=strtotime("$year-$month-01 -1 month");



		$prev_button=array('icon'=>'arrow-left', 'title'=>sprintf(_("Employees' calendar %s"), strftime("%b %Y", $date)  ), 'reference'=>'timesheets/month/'.date('Ym', $date));

		$date=strtotime("$year-$month-01 +1 month");



		$next_button=array('icon'=>'arrow-right', 'title'=>sprintf(_("Employees' calendar %s"), strftime("%b %Y", $date)  ), 'reference'=>'timesheets/month/'.date('Ym', $date));

		break;
	case 'week':

		$year=substr($data['parent_key'], 0, 4);
		$week=substr($data['parent_key'], 4, 2);



		$up_button=array('icon'=>'arrow-up', 'title'=>sprintf(_("Employees' calendar %s"), $year   ) , 'reference'=>'timesheets/year/'.$year);

		$date=strtotime('week ');
		$title=sprintf(_("Employees' calendar week %s %s"), $week, $year );


		$date=strtotime($year."W".$week." -1 week");
		$prev_week=sprintf('%02d', date('W', $date));
		$prev_year=date('o', $date);


		$prev_button=array('icon'=>'arrow-left', 'title'=>sprintf(_("Employees' calendar week %s %s"), $prev_week, $prev_year ), 'reference'=>'timesheets/week/'.$prev_year.$prev_week);

		$date=strtotime($year."W".$week." +1 week");
		$next_week=sprintf('%02d', date('W', $date));
		$next_year=date('o', $date);


		$next_button=array('icon'=>'arrow-right', 'title'=>sprintf(_("Employees' calendar week %s %s"), $next_week, $next_year ), 'reference'=>'timesheets/week/'.$next_year.$next_week);


		break;



	case 'year':

		$year=$data['parent_key'];

		$date=strtotime('week ');
		$title=sprintf(_("Employees' calendar %s"), $year ) ;
		$prev_button=array('icon'=>'arrow-left', 'title'=>sprintf(_("Employees' calendar %s"), ($year-1) ), 'reference'=>'timesheets/year/'.($year-1));

		$next_button=array('icon'=>'arrow-right', 'title'=>sprintf(_("Employees' calendar %s"), ($year+1) ), 'reference'=>'timesheets/year/'.($year+1));

		break;
	default:
		$title='';
		break;
	}


	$left_buttons[]=$prev_button;
	if (isset($up_button))
		$left_buttons[]=$up_button;
	$left_buttons[]=$next_button;

	$sections=get_sections('hr', '');

	$right_buttons[]=array('icon'=>'calendar-o', 'title'=>_('Today'), 'reference'=>'timesheets/day/'.date('Ymd'));
	$right_buttons[]=array('icon'=>'calendar-plus-o', 'title'=>_('This week'), 'reference'=>'timesheets/week/'.date('oW'));
	$right_buttons[]=array('icon'=>'calendar', 'title'=>_('This month'), 'reference'=>'timesheets/month/'.date('Ym'));


	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search manpower'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>
