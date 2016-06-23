<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 17:24:35 GMT+7 Bangkok, Thailand

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_suppliers_navigation($data, $smarty, $user, $db, $account) {


	$block_view=$data['section'];
	$left_buttons=array();

	$right_buttons=array();
	$sections=get_sections('suppliers', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Suppliers'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_purchase_orders_navigation($data, $smarty, $user, $db, $account) {


	$left_buttons=array();

	$right_buttons=array();
	$sections=get_sections('suppliers', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Purchase orders'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_agents_navigation($data, $smarty, $user, $db, $account) {


	$left_buttons=array();

	$right_buttons=array();
	$sections=get_sections('suppliers', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Agents'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



function get_suppliers_categories_navigation($data, $smarty, $user, $db) {





	$block_view=$data['section'];



	$left_buttons=array();

	$right_buttons=array();

	// $right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('suppliers', '');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Suppliers's Categories"),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_suppliers_category_navigation($data, $smarty, $user, $db, $account) {}


function get_suppliers_dashboard_navigation($data, $smarty, $user, $db, $account) {







	$block_view=$data['section'];



	$left_buttons=array();



	$right_buttons=array();


	$sections=get_sections('suppliers', $store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's Dashboard"),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_supplier_navigation($data, $smarty, $user, $db, $account) {


	$supplier=$data['_object'];


	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='suppliers';
			$_section='suppliers';
			break;
		case 'category':
			$tab='supplier.categories';
			$_section='categories';
			break;
		default:
			return '';

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
		$_order_field_value=$supplier->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($data['parent']=='account') {

			if ($result2=$db->query($sql)) {
				if ($row2 = $result2->fetch()) {
					if ( $row2['num']>1) {


						$sql=sprintf("select `Supplier Name` object_name,S.`Supplier Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND S.`Supplier Key` < %d))  order by $_order_field desc , S.`Supplier Key` desc limit 1",

							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$supplier->id
						);

						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {
								$prev_key=$row['object_key'];
								$prev_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';
							}
						}else {
							print_r($error_info=$db->errorInfo());
							exit;
						}





						$sql=sprintf("select `Supplier Name` object_name,S.`Supplier Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND S.`Supplier Key` > %d))  order by $_order_field   , S.`Supplier Key`  limit 1",
							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$supplier->id
						);

						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {
								$next_key=$row['object_key'];
								$next_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

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



						$up_button=array('icon'=>'arrow-up', 'title'=>_("Suppliers"), 'reference'=>'suppliers');

						if ($prev_key) {
							$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'supplier/'.$prev_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

						}
						$left_buttons[]=$up_button;


						if ($next_key) {
							$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'supplier/'.$next_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

						}


					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}









		}
		elseif ($data['parent']=='category') {



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







			//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit supplier'),'url'=>'edit_supplier.php?id='.$supplier->id);
			$right_buttons[]=array('icon'=>'sticky-note', 'title'=>_('Sticky note'), 'id'=>'sticky_note_button');
			//$right_buttons[]=array('icon'=>'sticky-note-o','title'=>_('History note'),'id'=>'note');
			//$right_buttons[]=array('icon'=>'paperclip','title'=>_('Attachement'),'id'=>'attach');
			//$right_buttons[]=array('icon'=>'shopping-cart','title'=>_('New order'),'id'=>'take_order');

		}

	}

	$sections=get_sections('suppliers', '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<span class="id Supplier_Code">'.$supplier->get('Code').'</span>';




	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_supplier_navigation($data, $smarty, $user, $db, $account) {

	$left_buttons=array();
	$right_buttons=array();








	if ($data['parent']=='agent') {
		$up_button=array('icon'=>'arrow-up', 'title'=>_("Agent"), 'reference'=>'agent/'.$data['parent_key']);
		$_section='agents';
	}else {

		$up_button=array('icon'=>'arrow-up', 'title'=>_("Suppliers"), 'reference'=>'suppliers');
		$_section='suppliers';
	}
		if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$sections=get_sections('suppliers', '');
	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._('New supplier').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_agent_navigation($data, $smarty, $user, $db, $account) {


	$agent=$data['_object'];


	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'account':
			$tab='agents';
			$_section='agents';
			break;

		default:
			return '';

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
		$_order_field_value=$agent->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($data['parent']=='account') {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Agents"), 'reference'=>'agents');


			if ($result2=$db->query($sql)) {
				if ($row2 = $result2->fetch()) {
					if ( $row2['num']>1) {


						$sql=sprintf("select `Agent Name` object_name,A.`Agent Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND A.`Agent Key` < %d))  order by $_order_field desc , A.`Agent Key` desc limit 1",

							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$agent->id
						);

						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {
								$prev_key=$row['object_key'];
								$prev_title=_("Agent").' '.$row['object_name'].' ('.$row['object_key'].')';
							}
						}else {
							print_r($error_info=$db->errorInfo());
							exit;
						}





						$sql=sprintf("select `Agent Name` object_name,A.`Agent Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND A.`Agent Key` > %d))  order by $_order_field   , A.`Agent Key`  limit 1",
							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$agent->id
						);

						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {
								$next_key=$row['object_key'];
								$next_title=_("Agent").' '.$row['object_name'].' ('.$row['object_key'].')';

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





						if ($prev_key) {
							$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'agent/'.$prev_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

						}
						$left_buttons[]=$up_button;


						if ($next_key) {
							$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'agent/'.$next_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

						}


					}
					else {
						$left_buttons[]=$up_button;
					}

				}
				else {
					$left_buttons[]=$up_button;
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}









		}


	}

	$sections=get_sections('suppliers', '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<i class="fa fa-user-secret" aria-hidden="true"></i> <span class="id Agent_Code">'.$agent->get('Code').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_new_agent_navigation($data, $smarty, $user, $db, $account) {

	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('suppliers', '');

	$_section='agents';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Agents"), 'reference'=>'agents');


	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._('New agent').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_supplier_part_navigation($data, $smarty, $user, $db, $account) {




	require_once 'class.Supplier.php';



	$supplier=new Supplier($data['key']);


	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'supplier':
			$tab='supplier.supplier_parts';
			$_section='suppliers';
			break;
		case 'category':
			$tab='supplier.categories';
			$_section='categories';
			break;
		default:
			return '';

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



		$_order_field_value=$data['_object']->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");


		if ($data['parent']=='supplier') {

			if ($result2=$db->query($sql)) {
				if ($row2 = $result2->fetch()) {


					if ( $row2['num']>1) {


						$sql=sprintf("select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND `Supplier Part Key` < %d))  order by $_order_field desc , `Supplier Part Key` desc limit 1",
							"$table $where $wheref",
							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$data['key']
						);
						//print $sql;
						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {

								$prev_key=$row['object_key'];
								$prev_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';
							}
						}else {
							print_r($error_info=$db->errorInfo());
							exit;
						}





						$sql=sprintf("select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND `Supplier Part Key` > %d))  order by $_order_field   , `Supplier Part Key`  limit 1",
							"$table $where $wheref",
							prepare_mysql($_order_field_value),
							prepare_mysql($_order_field_value),
							$data['key']
						);

						if ($result=$db->query($sql)) {
							if ($row = $result->fetch()) {
								$next_key=$row['object_key'];
								$next_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

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



						$up_button=array('icon'=>'arrow-up', 'title'=>_("Supplier").' '.$data['_parent']->get('Code'), 'reference'=>'supplier/'.$data['_parent']->id);

						if ($prev_key) {
							$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'supplier/'.$data['_parent']->id.'/part/'.$prev_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

						}
						$left_buttons[]=$up_button;


						if ($next_key) {
							$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'supplier/'.$data['_parent']->id.'/part/'.$next_key);

						}else {
							$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

						}


					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}









		}
		elseif ($data['parent']=='category') {



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







			//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit supplier'),'url'=>'edit_supplier.php?id='.$supplier->id);
			$right_buttons[]=array('icon'=>'sticky-note', 'title'=>_('Sticky note'), 'id'=>'sticky_note_button');
			//$right_buttons[]=array('icon'=>'sticky-note-o','title'=>_('History note'),'id'=>'note');
			//$right_buttons[]=array('icon'=>'paperclip','title'=>_('Attachement'),'id'=>'attach');
			//$right_buttons[]=array('icon'=>'shopping-cart','title'=>_('New order'),'id'=>'take_order');

		}

	}

	$sections=get_sections($_section, '');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= '<i class="fa fa-stop"></i>  <span class="id Supplier_Part_Reference">'.$data['_object']->get('Reference').'</span>';
	$title.=' <small class="padding_left_10"> <i class="fa fa-long-arrow-right padding_left_10"></i> <i class="fa fa-square button" title="'._('Part').'" onCLick="change_view(\'/part/'.$data['_object']->part->id.'\')" ></i> <span class="Part_Part_Reference button"  onCLick="change_view(\'part/'.$data['_object']->part->id.'\')">'.$data['_object']->part->get('Reference').'</small>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}



function get_new_supplier_part_navigation($data, $smarty, $user, $db, $account) {

	$left_buttons=array();
	$right_buttons=array();


	$sections=get_sections('suppliers', '');

	$_section='suppliers';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Supplier").' ('.$data['_parent']->get('Code').')', 'reference'=>'supplier/'.$data['parent_key']);


	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._("New Supplier's part").'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_purchase_order_navigation($data, $smarty, $user, $db, $account) {



	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'supplier':
			$tab='supplier.orders';
			$_section='suppliers';
			break;
		case 'agent':
			$tab='agent.orders';
			$_section='agents';
			break;
		case 'account':
			$tab='suppliers.orders';
			$_section='orders';
			break;
		case 'delivery_note':
			$tab='delivery_note.orders';
			$_section='delivery_notes';
			break;
		case 'invoice':
			$tab='invoice.orders';
			$_section='invoices';
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

		}

		$parameters['parent']=$data['parent'];
		$parameters['parent_key']=$data['parent_key'];



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
			if ($row2 = $result2->fetch()) {
				if ($row2['num']>1) {


					$sql=sprintf("select `Purchase Order Public ID` object_name,O.`Purchase Order Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Purchase Order Key` < %d))  order by $_order_field desc , O.`Purchase Order Key` desc limit 1",

						prepare_mysql($_order_field_value),
						prepare_mysql($_order_field_value),
						$object->id
					);


					if ($result=$db->query($sql)) {
						if ($row = $result->fetch()) {
							$prev_key=$row['object_key'];
							$prev_title=_("Order").' '.$row['object_name'].' ('.$row['object_key'].')';
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}



					$sql=sprintf("select `Purchase Order Public ID` object_name,O.`Purchase Order Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Purchase Order Key` > %d))  order by $_order_field   , O.`Purchase Order Key`  limit 1",
						prepare_mysql($_order_field_value),
						prepare_mysql($_order_field_value),
						$object->id
					);

					if ($result=$db->query($sql)) {
						if ($row = $result->fetch()) {
							$next_key=$row['object_key'];
							$next_title=_("Order").' '.$row['object_name'].' ('.$row['object_key'].')';

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


			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}




		if ($data['parent']=='supplier') {


			$up_button=array('icon'=>'arrow-up', 'title'=>_("Customer").' '.$object->get('Order Customer Name'), 'reference'=>'customers/'.$object->get('Order Store Key').'/'.$object->get('Order Customer Key'));

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'customer/'.$object->get('Order Customer Key').'/order/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'customer/'.$object->get('Order Customer Key').'/order/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}
			$sections=get_sections('customers', $object->get('Order Store Key'));
			$search_placeholder=_('Search customers');


		}
		elseif ($data['parent']=='account') {
			$up_button=array('icon'=>'arrow-up', 'title'=>_("Purchase orders"), 'reference'=>'suppliers/orders');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'suppliers/order/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'suppliers/order/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}



			$sections=get_sections('orders', $object->get('Order Store Key'));

			$search_placeholder=_('Search orders');


		}
		elseif ($data['parent']=='delivery_note') {
			$delivery_note=new DeliveryNote($data['parent_key']);
			$up_button=array('icon'=>'arrow-up', 'title'=>_("Delivery Note").' ('.$delivery_note->get('Delivery Note ID').')', 'reference'=>'/delivery_notes/'.$delivery_note->get('Delivery Note Store Key').'/'.$data['parent_key']);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'order/'.$data['parent_key'].'/invoice/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'order/'.$data['parent_key'].'/invoice/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}



			$sections=get_sections('delivery_notes', $delivery_note->get('Delivery Note Store Key'));
			$search_placeholder=_('Search delivery notes');



		}
		elseif ($data['parent']=='invoice') {
			$invoice=new Invoice($data['parent_key']);
			$up_button=array('icon'=>'arrow-up', 'title'=>_("Invoice").' ('.$invoice->get('Invoice Public ID').')', 'reference'=>'/delivery_notes/'.$invoice->get('Invoice Store Key').'/'.$data['parent_key']);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'order/'.$data['parent_key'].'/invoice/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'order/'.$data['parent_key'].'/invoice/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}



			$sections=get_sections('invoices', $invoice->get('Invoice Store Key'));

			$search_placeholder=_('Search invoices');

		}
	}
	else {
		exit;

	}

	$sections=get_sections('suppliers', '');
	$search_placeholder=_('Search suppliers');

	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= _('Purchase Order').' <span class="id">'.$object->get('Public ID').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>$search_placeholder)

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_deleted_supplier_navigation($data, $smarty, $user, $db, $account) {


	$_section='barcodes';
	$object=$data['_object'];
	$block_view=$data['section'];
	$left_buttons=array();

	$right_buttons=array();
	$sections=get_sections('suppliers');
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$title=_('Deleted supplier').' <span class="id Supplier_Code">'.$object->get('Deleted Code').'</span>';

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_suppliers_new_main_category_navigation($data, $smarty, $user, $db, $account) {


	$sections=get_sections('suppliers', $data['parent_key']);
	$left_buttons=array();
	$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Categories'), 'reference'=>'suppliers/categories', 'parent'=>'');

	$right_buttons=array();

	$sections['categories']['selected']=true;



	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("New main suppliers's category"),
		'search'=>array('show'=>true, 'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;


}


?>
