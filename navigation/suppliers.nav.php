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


function get_suppliers_categories_navigation($data, $smarty, $user, $db, $account) {}


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




	require_once 'class.Supplier.php';



	$supplier=new Supplier($data['key']);


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


	//  {if $supplier->get_image_src()} <img id="avatar" src="{$supplier->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="/art/avatar.jpg" style="cursor:pointer;"> {/if} {if $supplier->get('Supplier Level Type')=='VIP'}<img src="/art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $supplier->get('Supplier Level Type')=='Partner'}<img src="/art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if}
	$avatar='<div class="square_button"></div>';
	$avatar='<div class="square_button left"><img id="avatar" style="height:100%" src="/art/avatar.jpg" style="cursor:pointer;"> </div> ';
	$avatar='';

	$title= '<span class="id Supplier_Name">'.$supplier->get('Name').' (<span class="Supplier_Code">'.$supplier->get('Code').'</span>)</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'avatar'=>$avatar,
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


	$sections=get_sections('suppliers', '');

	$_section='suppliers';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;

	$up_button=array('icon'=>'arrow-up', 'title'=>_("Suppliers"), 'reference'=>'suppliers');


	$left_buttons[]=$up_button;


	$title= '<span class="id ">'._('New Supplier').'</span>';


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


						$sql=sprintf("select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Supplier Part Key` < %d))  order by $_order_field desc , `Supplier Part Key` desc limit 1",

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





						$sql=sprintf("select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Supplier Part Key` > %d))  order by $_order_field   , `Supplier Part Key`  limit 1",
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
	$title.=' <small class="padding_left_10"> <i class="fa fa-long-arrow-right padding_left_10"></i> <i class="fa fa-square button" title="'._('Part').'" onCLick="change_view(\'part/'.$data['_object']->part->id.'\')" ></i> <span class="Part_Reference button"  onCLick="change_view(\'part/'.$data['_object']->part->id.'\')">'.$data['_object']->part->get('Reference').'</small>';
	

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


?>
