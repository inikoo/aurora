<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 17:24:35 GMT+7 Bangkok, Thailand

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_suppliers_navigation($data) {

	global $user,$smarty;



	$block_view=$data['section'];




	$left_buttons=array();



	$right_buttons=array();
	$sections=get_sections('suppliers','');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Suppliers'),
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_list_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';
	include_once 'class.List.php';

	include_once 'class.List.php';


	$list=new SubjectList($data['key']);
	$store=new Store($list->get('List Parent Key'));


	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();



	$tab='suppliers.lists';


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
	$order=preg_replace('/^.*\.`/','',$order);
	$order=preg_replace('/^`/','',$order);
	$order=preg_replace('/`$/','',$order);
	$_order_field_value=$list->get($order);


	$prev_title='';
	$next_title='';
	$prev_key=0;
	$next_key=0;
	$sql=trim($sql_totals." $wheref");
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

		$sql=sprintf("select `List Name` object_name,`List Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `List Key` < %d))  order by $_order_field desc , `List Key` desc limit 1",

			prepare_mysql($_order_field_value),
			prepare_mysql($_order_field_value),
			$list->id
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_key=$row['object_key'];
			$prev_title=_("List").' '.$row['object_name'].' ('.$row['object_key'].')';

		}

		$sql=sprintf("select `List Name` object_name,`List Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `List Key` > %d))  order by $_order_field   , `List Key`  limit 1",
			prepare_mysql($_order_field_value),
			prepare_mysql($_order_field_value),
			$list->id
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_key=$row['object_key'];
			$next_title=_("List").' '.$row['object_name'].' ('.$row['object_key'].')';

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




	$up_button=array('icon'=>'arrow-up','title'=>_("Supplier's lists").' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id.'/lists');

	if ($prev_key) {
		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/list/'.$prev_key);

	}else {
		$left_buttons[]=array('icon'=>'arrow-left disabled','title'=>'','url'=>'');

	}
	$left_buttons[]=$up_button;


	if ($next_key) {
		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/list/'.$next_key);

	}else {
		$left_buttons[]=array('icon'=>'arrow-right disabled','title'=>'','url'=>'');

	}










	$right_buttons=array();
	$sections=get_sections('suppliers',$store->id);

	$sections['lists']['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's List").' <span class="id">'.$list->get('List Name').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_categories_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Supplier's Categories").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Supplier's Categories").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/categories/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Suppliers').' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/categories/'.$next_key);
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit'),'url'=>"edit_supplier_categories.php?store_id=".$store->id);

	$sections=get_sections('suppliers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_category_navigation($data) {

	global $user,$smarty;


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
		
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_("Supplier's Categories").' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id.'/categories');

		
		
		break;

	default:

		break;
	}

	




	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit'),'url'=>"edit_supplier_categories.php?store_id=".$store->id);

	$sections=get_sections('suppliers',$store->id);
	$sections['categories']['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Category").' <span class="id">'.$category->get('Category Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_lists_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Supplier's Lists").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Supplier's Lists").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/'.$prev_key.'/lists');
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Suppliers').' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/'.$next_key.'/lists');
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'plus','title'=>_('New list'),'reference'=>"suppliers/".$store->id.'/lists/new');

	$sections=get_sections('suppliers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's Lists").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_dashboard_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Supplier's Dashboard").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Supplier's Dashboard").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/dashboard/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Suppliers').' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/dashboard/'.$next_key);
	}


	$right_buttons=array();


	$sections=get_sections('suppliers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's Dashboard").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_statistics_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Supplier's Stats").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Supplier's Stats").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/statistics/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Suppliers').' '.$store->data['Store Code'],'reference'=>'suppliers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/statistics/'.$next_key);
	}


	$right_buttons=array();


	$sections=get_sections('suppliers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Supplier's Stats").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_pending_orders_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Pending orders').' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Pending orders').' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/pending_orders/'.$prev_key);
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Pending orders (All stores)').' '.$store->data['Store Code'],'reference'=>'suppliers/all/pending_orders');

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/pending_orders/'.$next_key);
	}


	$right_buttons=array();
	$sections=get_sections('suppliers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Pending orders').' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_suppliers_server_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	$branch=array(array('label'=>'','icon'=>'home','reference'=>''));



	$left_buttons=array();

	if ($data['section']=='suppliers') {
		$title=_('Suppliers (All stores)');
	}else {
		$title=_('Pending orders (All stores)');
	}

	$right_buttons=array();
	$sections=get_sections('suppliers_server');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers all stores'))

	);
	$smarty->assign('content',$_content);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_supplier_navigation($data) {

	global $user,$smarty;


	require_once 'class.Supplier.php';



	$supplier=new Supplier($data['key']);


	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'suppliers':
			$tab='suppliers';
			$_section='suppliers';
			break;
		case 'category':
			$tab='supplier.categories';
			$_section='categories';
			break;
		case 'list':
			$tab='suppliers.list';
			$_section='lists';
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
		$order=preg_replace('/^.*\.`/','',$order);
		$order=preg_replace('/^`/','',$order);
		$order=preg_replace('/`$/','',$order);
		$_order_field_value=$supplier->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `Supplier Name` object_name,S.`Supplier Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND S.`Supplier Key` < %d))  order by $_order_field desc , S.`Supplier Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$supplier->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Supplier Name` object_name,S.`Supplier Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND S.`Supplier Key` > %d))  order by $_order_field   , S.`Supplier Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$supplier->id
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

		if ($data['parent']=='list') {

			include_once 'class.List.php';
			$list=new SubjectList($data['parent_key']);

			$up_button=array('icon'=>'arrow-up','title'=>_("List").' '.$list->data['List Name'],'reference'=>'suppliers/list/'.$list->id);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'suppliers/list/'.$data['parent_key'].'/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled','title'=>'','url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'suppliers/list/'.$data['parent_key'].'/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled','title'=>'','url'=>'');

			}


		}
		elseif ($data['parent']=='category') {



			include_once 'class.Category.php';
			$category=new Category($data['parent_key']);


			$category_keys=preg_split('/\>/',preg_replace('/\>$/','',$category->data['Category Position']));
			array_pop($category_keys);
			if (count($category_keys)>0) {
				$sql=sprintf("select `Category Code`,`Category Key` from `Category Dimension` where `Category Key` in (%s)",join(',',$category_keys));
				//print $sql;
				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

					$branch[]=array('label'=>$row['Category Code'],'icon'=>'','url'=>'supplier_category.php?id='.$row['Category Key']);

				}
			}


			$up_button=array('icon'=>'arrow-up','title'=>_("Category").' '.$category->data['Category Code'],'url'=>'supplier_category.php?id='.$category->id);

		}

		else {

			$up_button=array('icon'=>'arrow-up','title'=>_("Suppliers"),'reference'=>'suppliers');

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'supplier/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled','title'=>'','url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'supplier/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled','title'=>'','url'=>'');

			}


		}



	}
    else{
    			$_section='suppliers';

    }
	//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit supplier'),'url'=>'edit_supplier.php?id='.$supplier->id);
	$right_buttons[]=array('icon'=>'sticky-note','title'=>_('Sticky note'),'id'=>'sticky_note_button');
	//$right_buttons[]=array('icon'=>'sticky-note-o','title'=>_('History note'),'id'=>'note');
	//$right_buttons[]=array('icon'=>'paperclip','title'=>_('Attachement'),'id'=>'attach');
	//$right_buttons[]=array('icon'=>'shopping-cart','title'=>_('New order'),'id'=>'take_order');

	$sections=get_sections('suppliers','');


	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	//  {if $supplier->get_image_src()} <img id="avatar" src="{$supplier->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="/art/avatar.jpg" style="cursor:pointer;"> {/if} {if $supplier->get('Supplier Level Type')=='VIP'}<img src="/art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $supplier->get('Supplier Level Type')=='Partner'}<img src="/art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if}
	$avatar='<div class="square_button"></div>';
	$avatar='<div class="square_button left"><img id="avatar" style="height:100%" src="/art/avatar.jpg" style="cursor:pointer;"> </div> ';
	$avatar='';

	$title= '<span class="id">'.$supplier->get('Name').' ('.$supplier->get('Code').')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'avatar'=>$avatar,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search suppliers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}

function get_new_supplier_navigation($data, $smarty, $user, $db) {




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

?>
