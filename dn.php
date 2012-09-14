<?php
include_once 'common.php';
include_once 'class.CurrencyExchange.php';
include_once('class.CompanyArea.php');
include_once 'class.Order.php';
if (!$user->can_view('orders')) {
	header('Location: index.php');
	exit;
}









$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/edit.css',
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
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'warehouse_orders.js.php',

	'js/common_assign_picker_packer.js.php',
	'js/csv_common.js'
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
	$yui_path.'calendar/calendar-min.js',

	'js/common.js',
	'js/table_common.js',
	'js/search.js',
//	'dn.js.php',
	'js/common_assign_picker_packer.js.php',
	
	
);


if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
	header('Location: orders_server.php?msg=wrong_id');
	exit;
}


$dn_id=$_REQUEST['id'];
$_SESSION['state']['dn']['id']=$dn_id;
$dn=new DeliveryNote($dn_id);


if (!$dn->id) {
	header('Location: orders_server.php?msg=order_not_found');
	exit;

}
if (!($user->can_view('stores') and in_array($dn->data['Delivery Note Store Key'],$user->stores)   ) ) {
	header('Location: orders_server.php');
	exit;
}

//$dn->update_xhtml_invoices();


$customer=new Customer($dn->get('Delivery Note Customer Key'));
$store=new Store($dn->get('Delivery Note Store Key'));
$warehouse=new Warehouse($dn->data['Delivery Note Warehouse Key']);

$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');

$smarty->assign('store_id',$store->id);
$smarty->assign('warehouse',$warehouse);


//if ($dn->data['Delivery Note State']=='Dispatched') {
	$js_files[]='dn.js.php';
	$template='dn.tpl';
//}else {
//	$js_files[]='dn_in_process.js.php';
//	$template='dn_in_process.tpl';
//}

$_SESSION['state']['dn']['store_key']=$dn->data['Delivery Note Store Key'];


$tipo_filter=$_SESSION['state']['products']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['products']['table']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code')
	,'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code')
	,'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100);
$smarty->assign('paginator_menu0',$paginator_menu);

$company_area=new CompanyArea('code','WAH');
$pickers=$company_area->get_current_staff_with_position_code('PICK');
$number_cols=5;
$row=0;
$pickers_data=array();
$contador=0;
foreach($pickers as $picker) {
    if (fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
    $tmp=array();
    foreach($picker as $key=>$value) {
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
foreach($packers as $packer) {
    if (fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
    $tmp=array();
    foreach($packer as $key=>$value) {
        $tmp[preg_replace('/\s/','',$key)]=$value;
    }
    $packers_data[$row][]=$tmp;
    $contador++;
}

$smarty->assign('packers',$packers_data);

$tipo_filter2='alias';
$filter_menu2=array(
                  'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
                  'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
              );
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');



$smarty->assign('dn',$dn);
$smarty->assign('customer',$customer);
$smarty->assign('store',$store);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Delivery Note').' '.$dn->get('Delivery Note Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
//print $template;
$smarty->display($template);
?>
