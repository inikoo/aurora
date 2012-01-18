<?php
include_once('common.php');
include_once('class.CurrencyExchange.php');



include_once('class.Order.php');
if(!$user->can_view('orders')){
  header('Location: index.php');
   exit;
}

  $modify=$user->can_edit('orders');
  
  if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
    header('Location: orders_server.php?msg=wrong_id');
   exit;
}

 $general_options_list=array();
$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;
$order=new Order($order_id);
if(!$order->id){
   header('Location: orders_server.php?msg=order_not_found');
   exit;

}
if(!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ){
  header('Location: orders_server.php');
   exit;
}

$customer=new Customer($order->get('order customer key'));
  
  
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 'text_editor.css',
		 'common.css',
		 'css/container.css',
		 'table.css',
		 'css/edit_address.css',
		  'css/edit.css'
		 );



$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
                $yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'container/container-min.js',

		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'edit_address.js.php',
		'address_data.js.php?tipo=customer&id='.$customer->id,
		'edit_delivery_address_js/common.js',
		'js/edit_common.js',
		'new_post_order.js.php?order_key='.$order_id
		);








    $template='new_post_order.tpl';
    
   
    $_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];
    $smarty->assign('show_all',$_SESSION['state']['order']['show_all']);
    
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


$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('order',$order);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$order_post_transactions_in_process=$order->get_post_transactions_in_process_data();
$smarty->assign('order_post_transactions_in_process',$order_post_transactions_in_process);


$smarty->display($template);
?>
