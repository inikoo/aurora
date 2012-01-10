<?php
include_once('common.php');
include_once('class.CurrencyExchange.php');

include_once('class.Order.php');
if(!$user->can_view('orders')){
  header('Location: index.php');
   exit;
}
  
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 'text_editor.css',
		 'common.css',
		 'css/container.css',
		 'table.css',
		'button.css',
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
		'js/table_common.js'
		);


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
    header('Location: orders_server.php?msg=wrong_id');
   exit;
}


$dn_id=$_REQUEST['id'];
$_SESSION['state']['dn']['id']=$dn_id;
$dn=new DeliveryNote($dn_id);

if(!$dn->id){
   header('Location: orders_server.php?msg=order_not_found');
   exit;

}
if(!($user->can_view('stores') and in_array($dn->data['Delivery Note Store Key'],$user->stores)   ) ){
  header('Location: orders_server.php');
   exit;
}

$customer=new Customer($dn->get('Delivery Note Customer Key'));



if($dn->data['Delivery Note State']=='Dispatched'){
$js_files[]='dn.js.php';
$template='dn.tpl';
}else{
$js_files[]='dn_in_process.js.php';
$template='dn_in_process.tpl';
}
   
    $_SESSION['state']['dn']['store_key']=$dn->data['Delivery Note Store Key'];

    
    $tipo_filter=$_SESSION['state']['products']['table']['f_field'];
    $smarty->assign('filter',$tipo_filter);
    $smarty->assign('filter_value',$_SESSION['state']['products']['table']['f_value']);
    $filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code')
		   ,'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code')
		   ,'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')
		   
		     );
    $smarty->assign('filter_menu0',$filter_menu);
    $smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
    
    
    $paginator_menu=array(10,25,50,100);
    $smarty->assign('paginator_menu0',$paginator_menu);



$smarty->assign('dn',$dn);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Delivery Note').' '.$dn->get('Delivery Note Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);
?>