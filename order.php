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
		 'container.css',
		 'table.css'
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
		'common.js.php',
		'table_common.js.php'
		);

if(isset($_REQUEST['new']) ){
  
  if(isset($_REQUEST['customer_key']) and is_numeric($_REQUEST['customer_key']) ){
    $customer=new Customer($_REQUEST['customer_key']);
    if(!$customer->id)
      $customer=new Customer('create anonymous');
  }else
    $customer=new Customer('create anonymous');
  $editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
		);
  
  $order_data=array('type'=>'system'
		    ,'Customer Key'=>$customer->id
		    ,'Order Type'=>'Order'
		    ,'editor'=>$editor
		    
		    );

  $order=new Order('new',$order_data);


  if($order->error)
    exit('error');
 
  $_SESSION['state']['order']['show_all']=true;
  header('Location: order.php?id='.$order->id);
  exit;
  


}



if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
    header('Location: orders_server.php?msg=wrong_id');
   exit;
}


$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;

if(!$order=new Order($order_id)){
   header('Location: orders_server.php?msg=order_not_found');
   exit;

}
if(!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ){
  header('Location: orders_server.php');
   exit;
}

$customer=new Customer($order->get('order customer key'));

if(isset($_REQUEST['pick_aid'])){
  $js_files[]='order_pick_aid.js.php';
  $template='order_pick_aid.tpl';
}else{


  switch($order->get('Order Current Dispatch State')){
    
  case('In Process'):

    $js_files[]='js/edit_common.js';
    $js_files[]='order_in_process.js.php?order_key='.$order_id;
    $template='order_in_process.tpl';
    
   
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
    $smarty->assign('filter_menu',$filter_menu);
    $smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
    
    
    $paginator_menu=array(10,25,50,100);
    $smarty->assign('paginator_menu',$paginator_menu);




   break;
  case('Dispached'):

    
    $js_files[]='order_dispached.js.php';
    $template='order_dispached.tpl';
  break; 
 case('Cancelled'):

    
    $js_files[]='order_cancelled.js.php';
    $template='order_cancelled.tpl';
  break; 
case('Unknown'):
 $js_files[]='order_unknown.js.php';
    $template='order_unknown.tpl';
break;
 default:
   exit('todo '.$order->get('Order Current Dispatch State'));
  break;
}  
}


$smarty->assign('order',$order);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($template);
?>