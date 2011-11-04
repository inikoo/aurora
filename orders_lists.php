<?php
include_once('common.php');
include_once('class.Store.php');
if(!$user->can_view('orders'))
  exit();

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
  $store_id=$_SESSION['state']['orders_lists']['store'];

}

if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);

$_SESSION['state']['orders_lists']['store']=$store_id;


$q='';


if(isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])){
$_SESSION['state']['orders_lists']['view']=$_REQUEST['view'];
}

$smarty->assign('block_view',$_SESSION['state']['orders_lists']['view']);


$block_view_label=array('orders'=>'Orders','invoices'=>'Invoices','dn'=>'Delivery Notes');

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse_orders.php','label'=>_('Warehouse Operations'));
$general_options_list[]=array('tipo'=>'url','url'=>'orders.php?view=dn','label'=>_('Delivery Notes'));

$general_options_list[]=array('tipo'=>'url','url'=>'orders.php?view=orders','label'=>_('Orders'));
$general_options_list[]=array('tipo'=>'url','url'=>'orders.php?view=invoices','label'=>_('Invoices'));


$general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'new_list_button','label'=>_('New List').' ('.$block_view_label[$_SESSION['state']['orders_lists']['view']].')');

$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');








$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 
		 'button.css',
		 'container.css',
		 'common.css'
		 
		 );

$css_files[]='theme.css.php';


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
		'js/edit_common.js',
                'js/csv_common.js',
		'orders_lists.js.php'
		);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter0=($q==''?$_SESSION['state']['orders_lists']['orders']['f_field']:'name');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['orders_lists']['orders']['f_value']:addslashes($q)));
$filter_menu0=array(
		   'name'=>array('db_key'=>'name','menu_label'=>'List name starting with  <i>x</i>','label'=>'List Name'),
		   );
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

$tipo_filter1=$_SESSION['state']['orders_lists']['invoices']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',($_SESSION['state']['orders_lists']['invoices']['f_value']));
$filter_menu1=array(
		   'name'=>array('db_key'=>'name','menu_label'=>'List name starting with  <i>x</i>','label'=>'List Name'),
		   );
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);

$tipo_filter2=$_SESSION['state']['orders_lists']['dn']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['orders_lists']['dn']['f_value']));
$filter_menu2=array(
		   'name'=>array('db_key'=>'name','menu_label'=>'List name starting with  <i>x</i>','label'=>'List Name'),
			   );
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);


$smarty->display('orders_lists.tpl');
?>
