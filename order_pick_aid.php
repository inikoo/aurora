<?php
include_once('common.php');
include_once('class.CurrencyExchange.php');

include_once('class.Order.php');
if(!$user->can_view('orders')){
  header('Location: index.php');
   exit;
}
  
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
$dn->update_picking_percentage();

  
  
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 'text_editor.css',
		 'common.css',
		 'container.css',
		 'table.css',
		  'css/edit.css'
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
		'table_common.js.php',
		'js/edit_common.js',
		'order_pick_aid.js.php?dn_key='.$dn->id
		);




  $template='order_pick_aid.tpl';

$customer=new Customer($dn->data['Delivery Note Customer Key']);

$smarty->assign('delivery_note',$dn);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Picking Aid Sheet').' '.$dn->get('Delivery Note Title') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($template);
?>