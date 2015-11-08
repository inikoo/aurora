<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 13:55:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_employees_navigation($data) {

	global $user, $smarty;


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

function get_employee_navigation($data) {

	global $smarty;

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


		$number_results=$_SESSION['table_state'][$tab]['nr'];
		$start_from=0;
		$order=$_SESSION['table_state'][$tab]['o'];
		$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
		$f_value=$_SESSION['table_state'][$tab]['f_value'];
		$parameters=$_SESSION['table_state'][$tab];

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
		
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND SD.`Staff Key` < %d))  order by $_order_field desc , SD.`Staff Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Staff Name` object_name,SD.`Staff Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND SD.`Staff Key` > %d))  order by $_order_field   , SD.`Staff Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

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

		if ($data['parent']=='group') {



			include_once 'class.Category.php';
			$category=new Category($data['parent_key']);


			$category_keys=preg_split('/\>/', preg_replace('/\>$/', '', $category->data['Category Position']));
			array_pop($category_keys);
			if (count($category_keys)>0) {
				$sql=sprintf("select `Category Code`,`Category Key` from `Category Dimension` where `Category Key` in (%s)", join(',', $category_keys));
				//print $sql;
				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

					$branch[]=array('label'=>$row['Category Code'], 'icon'=>'', 'url'=>'supplier_category.php?id='.$row['Category Key']);

				}
			}


			$up_button=array('icon'=>'arrow-up', 'title'=>_("Category").' '.$category->data['Category Code'], 'url'=>'supplier_category.php?id='.$category->id);

		}
		else {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Employees"), 'reference'=>'hr');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'employee/'.$data['parent_key'].'/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'employee/'.$data['parent_key'].'/'.$next_key);

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



	$title= '<span class="id Staff_Name">'.$object->get('Staff Name').'</span> <span class="id">('.$object->get_formated_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search users'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}

?>
