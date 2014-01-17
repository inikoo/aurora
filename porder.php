<?php

include_once 'common.php';
include_once 'class.Supplier.php';
include_once 'class.PurchaseOrder.php';




if (isset($_REQUEST['id'])) {



	$po=new PurchaseOrder($_REQUEST['id']);
	if (!$po->id)
		exit("Error po can no be found");
	$supplier=new Supplier('id',$po->data['Purchase Order Supplier Key']);
} elseif (isset($_REQUEST['new'])
	and isset($_REQUEST['supplier_id'])
	and is_numeric($_REQUEST['supplier_id'])
	and $_REQUEST['supplier_id']>0
) {
	$supplier=new Supplier('id',$_REQUEST['supplier_id']);
	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$data=array(
		'Purchase Order Supplier Key'=>$supplier->id
		,'Purchase Order Supplier Name'=>$supplier->data['Supplier Name']
		,'editor'=>$editor
	);

	$po=new PurchaseOrder('new',$data);
	if ($po->error)
		exit('error');



	$_SESSION['state']['porder']['products']['display']='ordered_products';

	header('Location: porder.php?id='.$po->id);
	exit;


} else {
	exit("error");

}

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'

);



$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
);





$po_id = $po->id;
$_SESSION['state']['porder']['id']=$po->id;
$_SESSION['state']['porder']['supplier_key']=$supplier->id;
$_SESSION['state']['supplier']['id']=$supplier->id;
//print_r($po->data);
$smarty->assign('po',$po);
$smarty->assign('supplier',$supplier);
$smarty->assign('title',_('Purchase Order').': '.$po->data['Purchase Order Public ID']);
$smarty->assign('view',$_SESSION['state']['porder']['view']);

$tipo_filter=$_SESSION['state']['porder']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['porder']['products']['f_value']);
$filter_menu=array(
	'p.code'=>array('db_key'=>'p.code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
);



$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('parent','suppliers');
switch ($po->data['Purchase Order Current Dispatch State']) {
case('In Process'):



	$smarty->assign('currency',$myconf['currency_symbol']);
	$smarty->assign('decimal_point',$myconf['decimal_point']);
	$smarty->assign('thousand_sep',$myconf['thousand_sep']);

	$_SESSION['state']['porder']['products']['display']='ordered_products';
	$smarty->assign('products_display_type',$_SESSION['state']['porder']['products']['display']);


	$smarty->assign('date',date("Y-m-d"));
	$smarty->assign('time',date("H:i"));






	$submit_method=array(
		'Internet'=>array('fname'=>_('Internet')),
		'Telephone'=>array('fname'=>_('Telephone')),
		'Fax'=>array('fname'=>_('Fax')),
		'In Person'=>array('fname'=>_('In Person')),
		'Email'=>array('fname'=>_('Email')),
		'Post'=>array('fname'=>_('Post')),
		'Other'=>array('fname'=>_('Other'),'selected'=>true)

	);
	$smarty->assign('default_submit_method','Other');
	$smarty->assign('submit_method',$submit_method);

	$smarty->assign('user_alias',$user->data['User Alias']);
	$smarty->assign('user_staff_key',$user->data['User Parent Key']);





	$js_files[]='porder_in_process.js.php';
	$js_files[]='js/edit_common.js';
	$smarty->assign('css_files',$css_files);
	$smarty->assign('js_files',$js_files);
	$smarty->display('porder_in_process.tpl');



	break;
case('Submitted'):
	$_SESSION['state']['porder']['show_all']=false;

	$js_files[]='porder_submitted.js.php';
	$js_files[]='js/edit_common.js';
	$smarty->assign('css_files',$css_files);
	$smarty->assign('js_files',$js_files);
	$_SESSION['state']['porder']['products']['display']='ordered_products';
	$smarty->assign('products_display_type',$_SESSION['state']['porder']['products']['display']);


	$smarty->display('porder_submitted.tpl');


	break;
	break;
case('Cancelled'):
	$js_files[]='porder_cancelled.js.php';
	$smarty->assign('css_files',$css_files);
	$smarty->assign('js_files',$js_files);
	$smarty->display('porder_cancelled.tpl');


	break;
}


?>
