<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 13:01:56 BST, Sheffield, UK   

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_inventory_all_warehouses_navigation($data, $smarty, $user, $db, $account) {

	global $user,$smarty;

	$block_view=$data['section'];

	


	$left_buttons=array();



	$right_buttons=array();
	$sections=get_sections('inventory_server','');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Inventory (All warehouses)'),
		'search'=>array('show'=>true,'placeholder'=>_('Search inventory all warehouses'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_inventory_navigation($data, $smarty, $user, $db, $account) {

	global $user,$smarty;

	$block_view=$data['section'];

	switch ($data['parent']) {
	case 'warehouse':
		
		break;
	default:
		break;
	}


	$left_buttons=array();



	$right_buttons=array();
	$sections=get_sections('inventory',$data['warehouse']->id);

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;
$title=_('Inventory').' ('._('Parts').') <span class="id">'.$data['warehouse']->get('Code').'</span>';

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search inventory'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_part_navigation($data, $smarty, $user, $db, $account) {

	global $user, $smarty;



	$object=$data['_object'];

	$block_view=$data['section'];



	$left_buttons=array();


	if ($data['parent']) {

		switch ($data['parent']) {
		case 'warehouse':
			$tab='inventory.parts';
			$_section='warehouses';
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

			$sql=sprintf("select `Part Reference` object_name,P.`Part SKU` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Part SKU` < %d))  order by $_order_field desc , P.`Part SKU` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Part Reference` object_name,P.`Part SKU` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Part SKU` > %d))  order by $_order_field   , P.`Part SKU`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';

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
			
			    $warehouse= new Warehouse($data['parent_key']);
			$warehouse_key=$warehouse->id;
				$up_button=array('icon'=>'arrow-up', 'title'=>_("Inventory").' ('.$warehouse->get('Warehouse Code').')', 'reference'=>'inventory/'.$data['parent_key']);

				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'inventory/'.$data['parent_key'].'/part/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'inventory/'.$data['parent_key'].'/part/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}

				break;
			case 'department':
				$up_button=array('icon'=>'arrow-up', 'title'=>_("Department").' ('.$object->get('Product Main Department Code').')', 'reference'=>'store/'.$object->get('Product Store Key').'/department/'.$object->get('Product Main Department Key'));

				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'department/'.$data['parent_key'].'/product/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'department/'.$data['parent_key'].'/product/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}

				break;

			}





		}


	}
	else {
		$_section='products';

	}



	$right_buttons=array();
	//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$store->id.'/edit');
	$sections=get_sections('warehouses', $warehouse_key);
	$_section='warehouses';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Part').' <span class="id Part_Reference">'.$object->get('Part Reference').'</span> (<span class="id Part_SKU">'.$object->get('SKU').'</span>) ',
		'search'=>array('show'=>true, 'placeholder'=>_('Search parts'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	
	return $html;

}


function get_transactions_navigation($data, $smarty, $user, $db, $account) {

	global $user,$smarty;

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
	$sections=get_sections('inventory','');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Stock movements'),
		'search'=>array('show'=>true,'placeholder'=>_('Search inventory'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_categories_navigation($data, $smarty, $user, $db, $account) {

	global $user, $smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case '':
		
		break;
	default:

		break;
	}

	$block_view=$data['section'];



	$left_buttons=array();



	$right_buttons=array();

//	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=");

	$sections=get_sections('inventory','');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Parts's Categories"),
		'search'=>array('show'=>true, 'placeholder'=>_('Search inventory'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_customers_category_navigation($data, $smarty, $user, $db, $account) {

	global $user, $smarty;


	require_once 'class.Category.php';
	require_once 'class.Store.php';


	$category=new Category($data['key']);

	$left_buttons=array();
	$right_buttons=array();

	switch ($data['parent']) {
	case 'category':
		$parent_category=new Category($data['parent_key']);
		break;
	case 'store':
		$store=new Store($data['parent_key']);

		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_("Customer's Categories").' '.$store->data['Store Code'], 'reference'=>'customers/'.$store->id.'/categories');



		break;

	default:

		break;
	}






	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('customers', $store->id);
	$sections['categories']['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Category").' <span class="id">'.$category->get('Category Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>
