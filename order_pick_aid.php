<?php
include_once('common.php');
include_once('class.CurrencyExchange.php');

include_once('class.Order.php');
if(!$user->can_view('orders')){
  header('Location: index.php');
   exit;
}
  
  if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
    header('Location: warehouse_orders.php?msg=wrong_id');
   exit;
}


$dn_id=$_REQUEST['id'];
$_SESSION['state']['dn']['id']=$dn_id;
$dn=new DeliveryNote($dn_id);
if(!$dn->id){
   header('Location: warehouse_orders.php?msg=order_not_found');
   exit;

}



if(isset($_REQUEST['refresh'])){
$dn->actualize_inventory_transaction_facts();
}

$dn->update_picking_percentage();



$warehouse= new Warehouse($dn->data['Delivery Note Warehouse Key']);
$smarty->assign('warehouse',$warehouse);







//$dn->start_packing(1);

//print_r($dn);

$number_transactions=$dn->get_number_transactions();
$number_picked_transactions=$dn->get_number_picked_transactions();
$smarty->assign('picked',($number_picked_transactions>=$number_transactions?true:false));
$smarty->assign('number_transactions',$number_transactions);
$smarty->assign('number_picked_transactions',$number_picked_transactions);

  
  
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
		'js/common.js',
		'js/table_common.js',
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