<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 13:01:56 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'class.Warehouse.php';

function get_warehouses_navigation($data, $smarty, $user, $db, $account) {




	$block_view=$data['section'];




	$left_buttons=array();



	$right_buttons=array();
	$sections=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Warehouses'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search inventory all warehouses'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_new_warehouse_navigation($data, $smarty, $user, $db, $account) {




	$block_view=$data['section'];




	$left_buttons=array();

	$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Warehouses'), 'reference'=>'warehouses', 'parent'=>'');


	$right_buttons=array();
	$sections=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('New Warehouse'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search inventory all warehouses'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_warehouse_navigation($data, $smarty, $user, $db, $account) {




	$block_view=$data['section'];

	$warehouse=new Warehouse($data['key']);



	$left_buttons=array();
	$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Warehouses'), 'reference'=>'warehouses', 'parent'=>'');



	$right_buttons=array();
	$sections=get_sections($data['module'], $warehouse->id);

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$title=_('Warehouse').' <span  class="id Warehouse_Code" >'.$warehouse->get('Code').'</span>';

	if ( !$user->can_view('locations')   ) {


		$title=_('Access forbidden').' <i class="fa fa-lock "></i>';
	}elseif (   !in_array($data['key'], $user->warehouses)   ) {


		$title=' <i class="fa fa-lock padding_right_10"></i>'.$title;
	}

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search locations'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_locations_navigation($data, $smarty, $user, $db, $account) {




	$block_view=$data['section'];

	switch ($data['parent']) {
	case 'warehouse':
		$warehouse=new Warehouse($data['parent_key']);
		break;
	default:
		break;
	}


	$left_buttons=array();



	$right_buttons=array();
	$sections=get_sections($data['module'], $warehouse->id);

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Locations').' <span class="id">'.$warehouse->get('Warehouse Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search locations'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_location_navigation($data, $smarty, $user, $db, $account) {



	$warehouse=$data['warehouse'];
	$object=$data['_object'];

	$left_buttons=array();





	switch ($data['parent']) {
	case 'warehouse':
		$tab='warehouse.locations';
		$_section='locations';
		break;
	default:

		exit('location navigation no parent');
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
		if ($row2 = $result2->fetch() and $row2['num']>1) {


			$sql=sprintf("select `Location Code` object_name,L.`Location Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND L.`Location Key` < %d))  order by $_order_field desc , L.`Location Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);
			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$prev_key=$row['object_key'];
					$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
				}
			}


			$sql=sprintf("select `Location Code` object_name,L.`Location Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND L.`Location Key` > %d))  order by $_order_field   , L.`Location Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);

			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$next_key=$row['object_key'];
					$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
				}
			}





			if ($order_direction=='desc') {
				$_tmp1=$prev_key;
				$_tmp2=$prev_title;
				$prev_key=$next_key;
				$prev_title=$next_title;
				$next_key=$_tmp1;
				$next_title=$_tmp2;
			}


			switch ($data['parent']) {
			case 'warehouse':

				$up_button=array('icon'=>'arrow-up', 'title'=>_('Warehouse'), 'reference'=>'warehouse/'.$data['parent_key'], 'parent'=>'');



				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'locations/'.$data['parent_key'].'/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'locations/'.$data['parent_key'].'/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}

				break;






			}



		}


	}



	$right_buttons=array();
	$sections=get_sections($data['module'], $warehouse->id);

	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$title=_('Location').' <span  class="id Location_Code" >'.$data['_object']->get('Code').'</span>';

	if ( !$user->can_view('locations')   ) {


		$title=_('Access forbidden').' <i class="fa fa-lock "></i>';
	}elseif (   !in_array($warehouse->id, $user->warehouses)   ) {


		$title=' <i class="fa fa-lock padding_right_10"></i>'.$title;
	}

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search locations'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}




?>
