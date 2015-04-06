<?php
include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.List.php';


if (!$user->can_view('customers') ) {
	header('Location: index.php');
	exit;
}
$modify=$user->can_edit('customers');
$general_options_list=array();
if (isset($_REQUEST['id']))
	$id=$_REQUEST['id'];
else {
	header('Location: index.php?error=no_id_in_customers_list');
	exit;

}


$list=new SubjectList($id);
if (!$list->id) {
	header('Location: index.php?error=id_in_customers_list_not_found');
	exit;
}


$store=new Store($list->data['List Parent Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('list',$list);


$smarty->assign('modify',$modify);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
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
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/customers_common.js',
	'js/export_common.js',
	'js/customers_list.js'


);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','customers');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['customers_list']['customers']['view']);

$smarty->assign('title', _('Customer List'));
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$tipo_filter=$_SESSION['state']['customers_list']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers_list']['customers']['f_value']);

$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),

	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$tipo_filter=$_SESSION['state']['customers_list']['offers']['f_field'];
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['customers_list']['offers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_menu4',$filter_menu);

$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);



$smarty->assign('table_key',1);


$smarty->assign('orders_type',$_SESSION['state']['customers_list']['customers']['orders_type']);
$smarty->assign('elements_activity',$_SESSION['state']['customers_list']['customers']['elements']['activity']);
$smarty->assign('elements_level_type',$_SESSION['state']['customers_list']['customers']['elements']['level_type']);
$smarty->assign('elements_customers_elements_type',$_SESSION['state']['customers_list']['customers']['elements_type']);


if($list->data['List Type']=='Dynamic'){
$block_view='customers';
}else{
$block_view=$_SESSION['state']['customers_list']['block_view'];
}
$smarty->assign('block_view',$block_view);



include 'customers_export_common.php';

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Id'=>_('ID'),
				'Customer_Name'=>_('Customer Name'),
				'Location'=>_('Location'),
				'Since'=>_('Since'),
				'Last_Order'=>_('Last Order'),
				'Orders'=>_('Orders'),
				'Status'=>_('Status'),
				'Contact_Name'=>_('Contact Name'),
				'Email'=>_('Email'),
				'Telephone'=>_('Telephone'),
				'Contact_Address'=>_('Contact Address'),
				'Billing_Address'=>_('Billing Address'),
				'Delivery_Address'=>_('Delivery Address'),
				'Payments'=>_('Payments'),
				'Refunds'=>_('Refunds'),
				'Balance'=>_('Balance'),
				'Outstanding'=>_('Outstanding'),
				'Profit'=>_('Profit'),
				'Orders_Rank'=>_('Orders Rank'),
				'Invoices_Rank'=>_('Invoices Rank'),
				'Balance_Rank'=>_('Balance Rank'),
				'Profits_Rank'=>_('Profits Rank'),
				'Logins'=>_('Logins'),
				'Failed_Logis'=>_('Failed Logis'),
				'Viewed_Pages'=>_('Viewed Pages'),
				'Category_Other_Value'=>_('Category Other Value'),
				'Code'=>_('Code'),
				'Label'=>_('Label'),
				'Customers'=>_('Customers'),
				'Date'=>_('Date'),
				'Time'=>_('Time'),
				'Author'=>_('Author'),
				'Notes'=>_('Notes'),

				'Page'=>_('Page'),
				'of'=>_('of')

			),
			'state'=>array('customers_list'=>$_SESSION['state']['customers_list'])
		)));
$smarty->assign('session_data', $session_data);
$smarty->display('customers_list.tpl');
?>
