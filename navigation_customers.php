<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2015 18:15:18 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_customers_navigation($data) {

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

	if ( $user->get_number_stores()>1) {
		$branch[]=array('label'=>_('Customers'),'icon'=>'bars','reference'=>'customers/all');
	}


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Customers').' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Customers').' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers (All stores)'),'reference'=>'customers/all','parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/'.$next_key );
	}


	$right_buttons=array();
	//$right_buttons[]=array('icon'=>'cog','title'=>_('Settings'),'url'=>'customer_store_configuration.php?store='.$store->id);
	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit customers'),'reference'=>'customers/'.$store->id.'/edit');
	$right_buttons[]=array('icon'=>'plus','title'=>_('New customer'),'id'=>"new_customer");
	$sections=get_sections('customers',$store->id);

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Customers').' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_categories_navigation($data) {

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

	$branch=array(array('label'=>'','icon'=>'home','reference'=>''));
	if ( $user->get_number_stores()>1) {
		$branch[]=array('label'=>_('Customers'),'icon'=>'bars','reference'=>'customers/all');
	}
	$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'],'icon'=>'users','reference'=>'customers/'.$store->id);


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Customer's Categories").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Customer's Categories").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/categories/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/categories/'.$next_key);
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit'),'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_lists_navigation($data) {

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
			$prev_title=_("Customer's Lists").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Customer's Lists").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/lists/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/lists/'.$next_key);
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'plus','title'=>_('New list'),'reference'=>"customers/lists/".$store->id.'/new');

	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Lists").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_dashboard_navigation($data) {

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
			$prev_title=_("Customer's Dashboard").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Customer's Dashboard").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/dashboard/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/dashboard/'.$next_key);
	}


	$right_buttons=array();


	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Dashboard").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_statistics_navigation($data) {

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
			$prev_title=_("Customer's Stats").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Customer's Stats").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/statistics/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/statistics/'.$next_key);
	}


	$right_buttons=array();


	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Stats").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_pending_orders_navigation($data) {

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


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/pending_orders/'.$prev_key);
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Pending orders (All stores)').' '.$store->data['Store Code'],'reference'=>'customers/all/pending_orders');

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/pending_orders/'.$next_key);
	}


	$right_buttons=array();
	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Pending orders').' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customers_server_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

	$branch=array(array('label'=>'','icon'=>'home','reference'=>''));



	$left_buttons=array();

	if ($data['section']=='customers') {
		$title=_('Customers (All stores)');
	}else {
		$title=_('Pending orders (All stores)');
	}

	$right_buttons=array();
	$sections=get_sections('customers_server');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search customers all stores'))

	);
	$smarty->assign('content',$_content);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_customer_navigation($data) {

	global $user,$smarty;


	require_once 'class.Customer.php';

    $customer=new Customer($data['key']);

	$block_view=$data['section'];



	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'store':
			$conf_table='customers';
			break;
		case 'category':
			$conf_table='customer_categories';
			break;
		case 'list':
			$conf_table='customers_list';
			break;
		}

		$conf=$_SESSION['state'][$conf_table]['customers'];


        $parent=$data['parent'];
        $parent_key=$data['parent_key'];
		$order=$conf['order'];
		$order_dir=$conf['order_dir'];
		$f_field=$conf['f_field'];
		$f_value=$conf['f_value'];
		$awhere=$conf['where'];
		$elements=$conf['elements'];

		$elements_type=$_SESSION['state'][$conf_table]['customers']['elements_type'];
		$orders_type=$_SESSION['state'][$conf_table]['customers']['orders_type'];
		$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



		include_once 'splinters/customers_prepare_list.php';

		$_order_field=$order;

		$order=preg_replace('/^.*\.`/','',$order);
		$order=preg_replace('/^`/','',$order);

		$order=preg_replace('/`$/','',$order);

		$_order_field_value=$customer->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql="select count(Distinct C.`Customer Key`) as num from $table   $where $wheref $where_type";
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `Customer Name` object_name,C.`Customer Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Customer Key` < %d))  order by $_order_field desc , C.`Customer Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$customer->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Customer").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Customer Name` object_name,C.`Customer Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Customer Key` > %d))  order by $_order_field   , C.`Customer Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$customer->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Customer").' '.$row['object_name'].' ('.$row['object_key'].')';

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

			$branch[]=array('label'=>_('Lists'),'icon'=>'list','url'=>'customers_lists.php?store='.$store->id);
			$branch[]=array('label'=>$list->data['List Name'],'icon'=>'','url'=>'customers_list.php?id='.$list->id);

			$up_button=array('icon'=>'arrow-up','title'=>_("List").' '.$list->data['List Name'],'reference'=>'customers_list.php?id='.$list->id);

		}
		elseif ($data['parent']=='category') {



			include_once 'class.Category.php';
			$category=new Category($data['parent_key']);

			$branch[]=array('label'=>_('Categories'),'icon'=>'sitemap','url'=>'customer_categories.php?id=0&store_id='.$store->id);

			$category_keys=preg_split('/\>/',preg_replace('/\>$/','',$category->data['Category Position']));
			array_pop($category_keys);
			if (count($category_keys)>0) {
				$sql=sprintf("select `Category Code`,`Category Key` from `Category Dimension` where `Category Key` in (%s)",join(',',$category_keys));
				//print $sql;
				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

					$branch[]=array('label'=>$row['Category Code'],'icon'=>'','url'=>'customer_category.php?id='.$row['Category Key']);

				}
			}


			$up_button=array('icon'=>'arrow-up','title'=>_("Category").' '.$category->data['Category Code'],'url'=>'customer_category.php?id='.$category->id);

		}

		else {

			$up_button=array('icon'=>'arrow-up','title'=>_("Customers").' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);




		}

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/'.$data['parent_key'].'/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled','title'=>'','url'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/'.$data['parent_key'].'/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled','title'=>'','url'=>'');

		}

	}

	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit customer'),'url'=>'edit_customer.php?id='.$customer->id);
	$right_buttons[]=array('icon'=>'sticky-note','title'=>_('Sticky note'),'id'=>'sticky_note_button');
	$right_buttons[]=array('icon'=>'sticky-note-o','title'=>_('History note'),'id'=>'note');
	$right_buttons[]=array('icon'=>'paperclip','title'=>_('Attachement'),'id'=>'attach');
	$right_buttons[]=array('icon'=>'shopping-cart','title'=>_('New order'),'id'=>'take_order');

	$sections=get_sections('customers',$customer->data['Customer Store Key']);
	
	$_section=$data['section'];
	if($_section=='customer')$_section='customers';
	//print_r($sections);
	//print $sections[$_section]['selected'];
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


//		{if $customer->get_image_src()} <img id="avatar" src="{$customer->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="/art/avatar.jpg" style="cursor:pointer;"> {/if} {if $customer->get('Customer Level Type')=='VIP'}<img src="/art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $customer->get('Customer Level Type')=='Partner'}<img src="/art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if} 
$avatar='<div class="square_button"></div>';
$avatar='<div class="square_button left"><img id="avatar" style="height:100%" src="/art/avatar.jpg" style="cursor:pointer;"> </div> ';

$title= '<span class="id">'.$customer->get('Customer Name').' ('.$customer->get_formated_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
	'avatar'=>$avatar,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);


	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

?>
