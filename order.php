<?php
include_once 'common.php';
include_once 'class.CurrencyExchange.php';

include_once 'class.CompanyArea.php';

include_once 'class.Store.php';

include_once 'class.Order.php';
if (!$user->can_view('orders')) {
	header('Location: index.php');
	exit;
}

$modify=$user->can_edit('orders');




$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
);


$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js'
);

if (isset($_REQUEST['new']) ) {
	date_default_timezone_set('UTC');
	if (isset($_REQUEST['customer_key']) and is_numeric($_REQUEST['customer_key']) ) {
		$customer=new Customer($_REQUEST['customer_key']);
		if (!$customer->id)
			$customer=new Customer('create anonymous');
	} else
		$customer=new Customer('create anonymous');
	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$order_data=array(

		'Customer Key'=>$customer->id,
		'Order Original Data MIME Type'=>'application/inikoo',
		'Order Type'=>'Order',
		'editor'=>$editor

	);


	$order=new Order('new',$order_data);


	if ($order->error)
		exit('error');


	$ship_to=$customer->get_ship_to();
	$order-> update_ship_to($ship_to->id);


	header('Location: order.php?id='.$order->id);
	exit;



}



if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
	header('Location: orders_server.php?msg=wrong_id');
	exit;
}

$general_options_list=array();
$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;
$order=new Order($order_id);

//$order->update_xhtml_delivery_notes();//exit;
//$order->update_no_normal_totals();

//$order->update_no_normal_totals();

//exit;

if (!$order->id) {
	header('Location: orders_server.php?msg=order_not_found');
	exit;

}
if (!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ) {
	header('Location: orders_server.php');
	exit;
}

if (isset($_REQUEST['referral'])) {
	$referral=$_REQUEST['referral'];
}else {
	$referral='';
}
$smarty->assign('referral',$referral);

$customer=new Customer($order->get('order customer key'));

//$order->update_no_normal_totals();
$order->update_totals_from_order_transactions();

$store=new Store($order->data['Order Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

if (isset($_REQUEST['pick_aid'])) {
	$js_files[]='order_pick_aid.js.php';
	$template='order_pick_aid.tpl';
} else {


	$tax_categories=array();
	$sql=sprintf("select * from `Tax Category Dimension` where `Tax Category Active`='Yes'");
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$tax_categories[]=array('rate'=>$row['Tax Category Rate'],'label'=>$row['Tax Category Name'],'code'=>$row['Tax Category Code'],'selected'=>($order->data['Order Tax Code']==$row['Tax Category Code']?true:false));
	}
	$smarty->assign('tax_categories',$tax_categories);

	$credit=array('net'=>'','tax_code'=>'','description'=>'','transaction_key'=>'');
	$sql=sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type`='Credit' and `Order Key`=%d",$order->id);
	$res=mysql_query($sql);
	$has_credit=0;
	if ($row=mysql_fetch_assoc($res)) {
		$credit=array('transaction_key'=>$row['Order No Product Transaction Fact Key'],'net'=>$row['Transaction Net Amount'],'tax_code'=>$row['Tax Category Code'],'description'=>$row['Transaction Description']);
		$has_credit=1;
	}
	$smarty->assign('credit',$credit);
	$smarty->assign('has_credit',$has_credit);

	//$order->update_no_normal_totals();
	//print $order->data['Order Balance Net Amount'].' '.$order->data['Order Balance Tax Amount'].' '.$order->data['Order Balance Total Amount'];
	switch ($order->get('Order Current Dispatch State')) {

	case('In Process'):


		$js_files[]='js/edit_common.js';


		$js_files[]='edit_address.js.php';
		$js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

		$js_files[]='edit_delivery_address_common.js.php';
		$js_files[]='order_in_process.js.php?order_key='.$order_id.'&customer_key='.$customer->id;
		$js_files[]='js/common_order_not_dispatched.js';

		$css_files[]='css/edit.css';
		$css_files[]='css/edit_address.css';


		$template='order_in_process.tpl';
		$_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];
		$smarty->assign('default_country_2alpha','GB');


		$products_display_type='ordered_products';

		$_SESSION['state']['order']['products']['display']=$products_display_type;

		$products_display_type=$_SESSION['state']['order']['products']['display'];
		$smarty->assign('products_display_type',$products_display_type);
		$smarty->assign('view',$_SESSION['state']['order']['products']['view']);

		$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


		$smarty->assign('filter0',$tipo_filter);
		$smarty->assign('filter_value0',$_SESSION['state']['order']['products']['f_value']);
		$filter_menu=array(
			'code'=>array('db_key'=>'code','menu_label'=>_('Code starting with <i>x</i>'),'label'=>_('Code')),
			'family'=>array('db_key'=>'family','menu_label'=>_('Family starting with <i>x</i>'),'label'=>_('Family')),
			'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name'))
		);
		$smarty->assign('filter_menu0',$filter_menu);
		$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


		$paginator_menu=array(10,25,50,100);
		$smarty->assign('paginator_menu0',$paginator_menu);

		$smarty->assign('search_label',_('Products'));
		$smarty->assign('search_scope','products');

		$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));





		break;



	case('Ready to Pick'):
	case('Picking & Packing'):
	case('Packed Done'):
	case('Ready to Ship'):


		if (isset($_REQUEST['amend']) and $_REQUEST['amend']) {


			$js_files[]='js/edit_common.js';


			$js_files[]='edit_address.js.php';
			$js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

			$js_files[]='edit_delivery_address_common.js.php';
			$js_files[]='order_in_warehouse_amend.js.php?order_key='.$order_id.'&customer_key='.$customer->id;
			$js_files[]='js/common_order_not_dispatched.js';


			$css_files[]='css/edit.css';
			$css_files[]='css/edit_address.css';


			$template='order_in_warehouse_amend.tpl';
			$_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];


			$products_display_type='ordered_products';

			$_SESSION['state']['order']['products']['display']=$products_display_type;

			$products_display_type=$_SESSION['state']['order']['products']['display'];
			$smarty->assign('products_display_type',$products_display_type);
			$smarty->assign('view',$_SESSION['state']['order']['products']['view']);

			$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


			$smarty->assign('filter0',$tipo_filter);
			$smarty->assign('filter_value0',$_SESSION['state']['order']['products']['f_value']);
			$filter_menu=array(
				'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
				'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
				'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

			);
			$smarty->assign('filter_menu0',$filter_menu);
			$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


			$paginator_menu=array(10,25,50,100);
			$smarty->assign('paginator_menu0',$paginator_menu);

			$smarty->assign('search_label',_('Orders'));
			$smarty->assign('search_scope','orders');

			$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));




		}
		else {





			$js_files[]='js/edit_common.js';


			$js_files[]='edit_address.js.php';
			$js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

			$js_files[]='edit_delivery_address_common.js.php';
			$js_files[]='order_in_warehouse.js.php?order_key='.$order_id.'&customer_key='.$customer->id;
			$js_files[]='js/common_order_not_dispatched.js';


			$css_files[]='css/edit.css';
			$css_files[]='css/edit_address.css';
			$js_files[]='js/common_assign_picker_packer.js.php';


			$template='order_in_warehouse.tpl';



			$_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];


			$products_display_type='ordered_products';
			$_SESSION['state']['order']['products']['display']=$products_display_type;

			$products_display_type=$_SESSION['state']['order']['products']['display'];

			$smarty->assign('products_display_type',$products_display_type);
			$smarty->assign('view',$_SESSION['state']['order']['products']['view']);




			$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


			$smarty->assign('filter0',$tipo_filter);
			$smarty->assign('filter_value0',$_SESSION['state']['order']['products']['f_value']);
			$filter_menu=array(
				'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
				'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
				'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

			);
			$smarty->assign('filter_menu0',$filter_menu);
			$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


			$paginator_menu=array(10,25,50,100);
			$smarty->assign('paginator_menu0',$paginator_menu);



			$tipo_filter2='alias';
			$filter_menu2=array(
				'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
				'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
			);
			$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
			$smarty->assign('filter_menu2',$filter_menu2);
			$smarty->assign('filter2',$tipo_filter2);
			$smarty->assign('filter_value2','');

			$smarty->assign('search_label',_('Orders'));
			$smarty->assign('search_scope','orders');

			$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

		}

		$company_area=new CompanyArea('code','WAH');
		$pickers=$company_area->get_current_staff_with_position_code('PICK');
		$number_cols=5;
		$row=0;
		$pickers_data=array();
		$contador=0;
		foreach ($pickers as $picker) {
			if (fmod($contador,$number_cols)==0 and $contador>0)
				$row++;
			$tmp=array();
			foreach ($picker as $key=>$value) {
				$tmp[preg_replace('/\s/','',$key)]=$value;
			}
			$pickers_data[$row][]=$tmp;
			$contador++;
		}

		$smarty->assign('pickers',$pickers_data);

		$packers=$company_area->get_current_staff_with_position_code('PACK');
		$number_cols=5;
		$row=0;
		$packers_data=array();
		$contador=0;
		foreach ($packers as $packer) {
			if (fmod($contador,$number_cols)==0 and $contador>0)
				$row++;
			$tmp=array();
			foreach ($packer as $key=>$value) {
				$tmp[preg_replace('/\s/','',$key)]=$value;
			}
			$packers_data[$row][]=$tmp;
			$contador++;
		}

		$smarty->assign('packers',$packers_data);

		break;


	case('Dispatched'):



		$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');
		$js_files[]='order_dispatched.js.php';
		$template='order_dispatched.tpl';


		$company_area=new CompanyArea('code','WAH');
		$pickers=$company_area->get_current_staff_with_position_code('PICK');
		$number_cols=5;
		$row=0;
		$pickers_data=array();
		$contador=0;
		foreach ($pickers as $picker) {
			if (fmod($contador,$number_cols)==0 and $contador>0)
				$row++;
			$tmp=array();
			foreach ($picker as $key=>$value) {
				$tmp[preg_replace('/\s/','',$key)]=$value;
			}
			$pickers_data[$row][]=$tmp;
			$contador++;
		}

		$smarty->assign('pickers',$pickers_data);

		$packers=$company_area->get_current_staff_with_position_code('PACK');
		$number_cols=5;
		$row=0;
		$packers_data=array();
		$contador=0;
		foreach ($packers as $packer) {
			if (fmod($contador,$number_cols)==0 and $contador>0)
				$row++;
			$tmp=array();
			foreach ($packer as $key=>$value) {
				$tmp[preg_replace('/\s/','',$key)]=$value;
			}
			$packers_data[$row][]=$tmp;
			$contador++;
		}

		$smarty->assign('packers',$packers_data);


		break;
	case('Cancelled'):
		$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');
		$smarty->assign('store_id',$store->id);
		$js_files[]='order_cancelled.js.php';
		$template='order_cancelled.tpl';
		break;
	case('Suspended'):
		$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');
		$smarty->assign('store_id',$store->id);

		$js_files[]='order_suspended.js.php';
		$template='order_suspended.tpl';
		break;
	case('Unknown'):
		$css_files[]='css/edit.css';
		$js_files[]='order_unknown.js.php';


		$products_display_type='ordered_products';
		$_SESSION['state']['order']['products']['display']=$products_display_type;

		$products_display_type=$_SESSION['state']['order']['products']['display'];

		$smarty->assign('products_display_type',$products_display_type);
		$smarty->assign('view',$_SESSION['state']['order']['products']['view']);




		$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


		$smarty->assign('filter0',$tipo_filter);
		$smarty->assign('filter_value0',$_SESSION['state']['order']['products']['f_value']);
		$filter_menu=array(
			'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
			'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
			'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

		);
		$smarty->assign('filter_menu0',$filter_menu);
		$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


		$paginator_menu=array(10,25,50,100);
		$smarty->assign('paginator_menu0',$paginator_menu);

		$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');


		$template='order_unknown.tpl';

		break;
		// case('Ready to Ship'):
		//  $js_files[]='order_ready_to_ship.js.php';
		//  $template='order_ready_to_ship.tpl';
		//  break;
	case 'In Process by Customer':
		$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');
		$smarty->assign('store_id',$store->id);
		$js_files[]='order_in_process_by_customer.js.php';
		$template='order_in_process_by_customer.tpl';
		break;
	break;
	default:


		exit('todo ->'.$order->get('Order Current Dispatch State').'<-');
		break;
	}
}
//$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('order',$order);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($template);
?>
